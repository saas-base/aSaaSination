<?php

namespace Core\Contracts;

interface ConditionalAutoRegistration
{
    public function registrationCondition(): bool;
}
