<?php

namespace Core\Events;

use Core\Abstracts\RepositoryEvent;

class RepositoryEntityUpdated extends RepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'updated';
}
