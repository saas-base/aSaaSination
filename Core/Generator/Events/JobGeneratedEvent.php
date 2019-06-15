<?php

namespace Core\Generator\Events;

use Core\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class JobGeneratedEvent
 * @package Base\Generator\Events
 */
class JobGeneratedEvent extends ResourceGeneratedEvent
{
    public function isSynchronous(): bool
    {
        return $this->getStubOption('sync');
    }
}
