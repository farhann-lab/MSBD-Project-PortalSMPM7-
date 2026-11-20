<?php

namespace App\Support\Migrations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

/**
 * Baseline MySQL 8.0 from the school schema-only dump.
 * Intentionally never DROP/ALTER pre-existing school tables.
 * MUST be run with: php artisan migrate --database=mysql
 */
final class SharedSchemaBaseline
{
    public static function table(string $name, string $ddl, array $expectedColumns, array $references = []): void
    {
        $db = DB::connection('mysql');
        self::assertMysql($db);

        $schema = Schema::connection('mysql');
        if ($schema->hasTable($name)) {
            $actual = array_map('strtolower', $schema->getColumnListing($name));
            $missing = array_diff(array_map('strtolower', $expectedColumns), $actual);
            if ($missing !== []) {
                throw new RuntimeException("Existing table {$name} is missing dump columns: " . implode(', ', $missing));
            }
            // Column existence is not full drift detection: compare types/FKs/indexes
            // against the manifest and SHOW CREATE TABLE before deploying.
            return;
        }

        foreach ($references as $referencedTable) {
            if (! $schema->hasTable($referencedTable)) {
                throw new RuntimeException("Cannot create {$name}: prerequisite {$referencedTable} is missing. Restore RKM first (or run optional legacy bootstrap on an EMPTY DEV database).");
            }
        }

        // The source dump contains legacy zero-date defaults. For DDL ONLY,
        // use the permissive SQL mode exported by mysqldump, then restore it.
        // This does not change the server's SQL mode or change any existing rows.
        $oldMode = $db->selectOne('SELECT @@SESSION.sql_mode AS sql_mode')->sql_mode;
        try {
            $db->statement("SET SESSION sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
            $db->unprepared($ddl);
        } finally {
            $db->statement('SET SESSION sql_mode = ?', [$oldMode]);
        }
    }

    public static function legacyTable(string $name, string $ddl, array $expectedColumns, array $references = []): void
    {
        self::requireDevOptIn('SIAKAD_ALLOW_LEGACY_BOOTSTRAP');
        self::table($name, $ddl, $expectedColumns, $references);
    }

    public static function object(string $type, string $name, string $ddl): void
    {
        self::requireDevOptIn('SIAKAD_ALLOW_OPTIONAL_OBJECT_BOOTSTRAP');
        $db = DB::connection('mysql');
        self::assertMysql($db);
        $type = strtoupper($type);
        $schema = $db->getDatabaseName();
        if ($type === 'VIEW') {
            $found = $db->selectOne(
                'SELECT COUNT(*) AS n FROM information_schema.VIEWS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
                [$schema, $name]
            )->n;
        } elseif ($type === 'TRIGGER') {
            $found = $db->selectOne(
                'SELECT COUNT(*) AS n FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = ? AND TRIGGER_NAME = ?',
                [$schema, $name]
            )->n;
        } elseif (in_array($type, ['FUNCTION', 'PROCEDURE'], true)) {
            $found = $db->selectOne(
                'SELECT COUNT(*) AS n FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ? AND ROUTINE_NAME = ? AND ROUTINE_TYPE = ?',
                [$schema, $name, $type]
            )->n;
        } else {
            throw new RuntimeException("Unsupported object type: {$type}");
        }
        if ((int) $found > 0) {
            return; // Never replace live VIEW/TRIGGER/ROUTINE definitions.
        }
        $db->unprepared($ddl);
    }

    private static function requireDevOptIn(string $flag): void
    {
        $dbName = (string) DB::connection('mysql')->getDatabaseName();
        if (app()->environment('production') || ! str_ends_with($dbName, '_dev') || ! filter_var(env($flag, false), FILTER_VALIDATE_BOOLEAN)) {
            throw new RuntimeException("Optional bootstrap disabled. Requires non-production, *_dev database and {$flag}=true after manual review.");
        }
    }

    private static function assertMysql($db): void
    {
        if ($db->getDriverName() !== 'mysql') {
            throw new RuntimeException('SIAKAD baseline migrations require native MySQL 8.0 connection named mysql.');
        }
        $version = (string) ($db->selectOne('SELECT VERSION() AS version')->version ?? '');
        if (stripos($version, 'MariaDB') !== false || ! preg_match('/^8\./', $version)) {
            throw new RuntimeException("This baseline targets MySQL 8.0 only; connected server is {$version}.");
        }
    }
}
