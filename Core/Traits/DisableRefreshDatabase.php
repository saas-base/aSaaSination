<?php

namespace Core\Traits;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait DisableRefreshDatabase
{
    use DatabaseMigrations {
        DatabaseMigrations::runDatabaseMigrations as parentMethod;
    }

    public function runDatabaseMigrations()
    {
    }
}
