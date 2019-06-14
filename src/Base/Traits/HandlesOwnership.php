<?php

namespace Base\Traits;

use Base\Contracts\Ownable;

trait HandlesOwnership
{
    public function hasAccess(?Ownable $model)
    {
        $this->authorize('access', $model);
    }
}
