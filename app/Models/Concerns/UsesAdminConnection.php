<?php

namespace App\Models\Concerns;

use App\Support\RoleDatabase;

/** Pilih akun admin hanya untuk model operasional admin, tidak berdasarkan role request. */
trait UsesAdminConnection
{
    public function getConnectionName()
    {
        // setConnection()/Model::on() tetap dapat menunjuk koneksi spesifik, misalnya mysql_public.
        return $this->connection ?? RoleDatabase::admin() ?? parent::getConnectionName();
    }
}
