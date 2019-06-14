<?php

namespace Repository\Events;

use Repository\Abstracts\AbstractRepositoryEvent;

class RepositoryEntityCreated extends AbstractRepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'created';
}
