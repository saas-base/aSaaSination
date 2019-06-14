<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class MigrationGeneratedEvent
 * @package Base\Generator\Events
 */
class MigrationGeneratedEvent extends ResourceGeneratedEvent
{
    public function getTableName()
    {
        return $this->getStubOption('table');
    }

    public function isMongoMigration()
    {
        return $this->getStubOption('mongo');
    }
}
