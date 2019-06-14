<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class ListenerGeneratedEvent
 * @package Base\Generator\Events
 */
class ListenerGeneratedEvent extends ResourceGeneratedEvent
{
    public function getEvent()
    {
        return $this->getStubOption('event');
    }

    public function getEventNamespace()
    {
        return $this->getStubOption('event_namespace');
    }

    public function isQueued()
    {
        return $this->getStubOption('queued');
    }
}
