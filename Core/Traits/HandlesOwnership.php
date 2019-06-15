<?php

namespace Core\Traits;

use Core\Contracts\Ownable;

trait HandlesOwnership
{
    public function hasAccess(?Ownable $model)
    {
        $this->authorize('access', $model);
    }
}
