<?php

namespace Base\Traits;

use Modules\User\Models\User;

trait OwnedByUser
{
    public function ownerId()
    {
        $ownerAttributeName = strtolower(get_short_class_name($this->ownedBy())).'_id';

        return $this->$ownerAttributeName;
    }

    public function ownedBy()
    {
        return User::class;
    }
}
