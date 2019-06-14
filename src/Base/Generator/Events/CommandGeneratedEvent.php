<?php

namespace Base\Generator\Events;

use Base\Generator\Abstracts\ResourceGeneratedEvent;

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
