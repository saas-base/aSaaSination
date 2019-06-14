<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

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
