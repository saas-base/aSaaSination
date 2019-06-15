<?php

namespace Core\Abstracts;

use Core\Traits\MutatesRequest;
use Core\Traits\HandlesOwnership;
use Core\Traits\HandlesNullResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HandlesOwnership, HandlesNullResource, MutatesRequest;
}
