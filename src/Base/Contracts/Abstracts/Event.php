<?php

namespace Base\Contracts\Abstracts;

use Base\Contracts\EventContract;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event implements EventContract
{
    use SerializesModels, Dispatchable;
}
