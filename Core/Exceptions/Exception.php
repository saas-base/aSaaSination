<?php

namespace Core\Exceptions;

use Core\Abstracts\AbstractException;

class Exception extends AbstractException
{
    protected $code = 500;
}
