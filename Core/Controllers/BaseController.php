<?php

namespace Core\Controllers;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function main()
    {
        return response('welcome to astral');
    }

    public function api()
    {
        return response('Astral api');
    }

    public function authorized()
    {
        return response('authorized');
    }
}
