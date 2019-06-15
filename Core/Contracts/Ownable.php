<?php

namespace Core\Contracts;

interface Ownable
{
    public function ownerId();

    public function ownedBy();
}
