<?php

namespace Core\Generator\Events;

use Core\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class ModelGeneratedEvent
 * @package Base\Generator\Events
 */
class ModelGeneratedEvent extends ResourceGeneratedEvent
{
    public function isMongoModel()
    {
        return $this->getStub()->getOptions()['MONGO'];
    }

    public function includesMigration()
    {
        return $this->getStub()->getOptions()['MIGRATION'];
    }
}
