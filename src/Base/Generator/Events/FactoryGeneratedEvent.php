<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class FactoryGeneratedEvent
 * @package Base\Generator\Events
 */
class FactoryGeneratedEvent extends ResourceGeneratedEvent
{
    public function getModel()
    {
        return $this->getStubOption('model');
    }

    public function getModelNamespace()
    {
        return $this->getStubOption('model_namespace');
    }
}
