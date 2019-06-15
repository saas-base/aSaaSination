<?php

namespace Core\Contracts;

use Modules\User\Models\User;

interface ModelPolicyContract
{
    public function create(User $user): bool;

    public function update(User $user, $model): bool;

    public function delete(User $user, $model): bool;
}
