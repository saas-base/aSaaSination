<?php

namespace Base\Contracts\Abstracts;

use Base\Traits\HandlesNullResource;
use Base\Traits\HandlesOwnership;
use Base\Traits\MutatesRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HandlesOwnership, HandlesNullResource, MutatesRequest;
}
