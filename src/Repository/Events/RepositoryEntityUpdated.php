<?php

namespace Repository\Events;

use Repository\Abstracts\AbstractRepositoryEvent;

class RepositoryEntityUpdated extends AbstractRepositoryEvent
{
    /**
     * @var string
     */
    protected $action = 'updated';
}
