<?php

namespace Repository\Events;

use Repository\Abstracts\AbstractRepositoryEvent;

class RepositoryEntityDeleted extends AbstractRepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'deleted';
}
