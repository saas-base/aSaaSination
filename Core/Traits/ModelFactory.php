<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\FactoryBuilder;

trait ModelFactory
{
    public static function fromFactory(?int $amount = null) :FactoryBuilder
    {
        return factory(static::class, $amount);
    }
}
