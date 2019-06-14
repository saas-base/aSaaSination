<?php

namespace Base\Contracts;

interface Ownable
{
    public function ownerId();

    public function ownedBy();
}
