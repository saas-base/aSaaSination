<?php

namespace Core\Events;

use Core\Abstracts\RepositoryEvent;

class RepositoryEntityDeleted extends RepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'deleted';
}
