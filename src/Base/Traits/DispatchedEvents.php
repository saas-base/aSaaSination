<?php

namespace Base\Traits;

use Base\Generator\Abstracts\ResourceGeneratedEvent;
use Base\Generator\Contracts\ResourceGenerationContract;
use Illuminate\Support\Facades\Event;

trait DispatchedEvents
{
    /**
     * @param string $class
     * @return ResourceGeneratedEvent[]
     */
    protected function getDispatchedEvents(?string $class): array
    {
        $events = [];
        Event::assertDispatched($class, function ($event) use (&$events) {
            $events[] = $event;

            return true;
        });

        return $events;
    }

    /**
     * @param string $class
     * @return ResourceGenerationContract
     */
    protected function getFirstDispatchedEvent(?string $class): ResourceGenerationContract
    {
        $events = $this->getDispatchedEvents($class);

        if (empty($events)) {
            return null;
        }

        return $events[0];
    }
}
