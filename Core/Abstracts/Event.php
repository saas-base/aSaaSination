<?php

namespace Core\Abstracts;

use Core\Contracts\EventContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

abstract class Event implements EventContract
{
    use SerializesModels, Dispatchable;
}
