<?php

namespace Core\Generator\Events;

use Core\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class RouteGeneratedEvent
 * @package Base\Generator\Events
 */
class RouteGeneratedEvent extends ResourceGeneratedEvent
{
    public function getVersion()
    {
        return $this->getStubOption('version');
    }
}
