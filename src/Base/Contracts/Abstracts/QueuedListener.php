<?php

namespace Base\Contracts\Abstracts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class QueuedListener extends Listener implements ShouldQueue
{
    use InteractsWithQueue;
}
