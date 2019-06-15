<?php

namespace Core\Contracts;

interface EventContract
{
    /**
     * Dispatch the event with the given arguments.
     *
     * @return void
     */
    public static function dispatch();

    /**
     * Broadcast the event with the given arguments.
     *
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    public static function broadcast();
}
