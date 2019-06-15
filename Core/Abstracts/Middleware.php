<?php

namespace Core\Abstracts;

use Closure;
use Illuminate\Http\Request;

abstract class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    abstract public function handle(Request $request, Closure $next);
}
