<?php

namespace Base\Exceptions;

use Base\Contracts\Abstracts\AbstractException;

class Exception extends AbstractException
{
    protected $code = 500;
}
