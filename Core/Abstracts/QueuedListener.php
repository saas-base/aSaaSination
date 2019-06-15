<?php

namespace Core\Abstracts;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class QueuedListener extends Listener implements ShouldQueue
{
    use InteractsWithQueue;
}
