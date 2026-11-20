<?php

namespace App\Models\Concerns;

use App\Support\RoleDatabase;

trait UsesPublicConnection
{
    public function getConnectionName()
    {
        return $this->connection ?? RoleDatabase::public() ?? parent::getConnectionName();
    }
}
