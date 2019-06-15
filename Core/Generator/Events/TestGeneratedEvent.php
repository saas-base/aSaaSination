<?php

namespace Core\Generator\Events;

use Core\Generator\Abstracts\ResourceGeneratedEvent;

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
