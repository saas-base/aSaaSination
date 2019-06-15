<?php

namespace Core\Exceptions;

class NotImplementedException extends \Exception
{
    protected $code = 500;

    protected $message = 'There is no implementation for this method yet';
}
