<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

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
