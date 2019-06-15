<?php

namespace Core\Generator\Events;

use Core\Generator\Abstracts\ResourceGeneratedEvent;

/**
 * Class CommandGeneratedEvent
 * @package Base\Generator\Events
 */
class CommandGeneratedEvent extends ResourceGeneratedEvent
{
    public function getConsoleCommand()
    {
        return $this->getStubOption('command_name');
    }
}
