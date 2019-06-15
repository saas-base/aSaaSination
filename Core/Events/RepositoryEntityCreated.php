<?php

namespace Core\Events;

use Core\Abstracts\RepositoryEvent;


class RepositoryEntityCreated extends RepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'created';
}
