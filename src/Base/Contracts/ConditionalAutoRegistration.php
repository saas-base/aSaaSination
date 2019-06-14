<?php

namespace Base\Contracts;

interface ConditionalAutoRegistration
{
    public function registrationCondition(): bool;
}
