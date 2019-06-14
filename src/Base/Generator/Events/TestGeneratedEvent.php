<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class TestGeneratedEvent
 * @package Base\Generator\Events
 */
class TestGeneratedEvent extends ResourceGeneratedEvent
{
    public function getType()
    {
        return $this->getStubOption('type');
    }
}
