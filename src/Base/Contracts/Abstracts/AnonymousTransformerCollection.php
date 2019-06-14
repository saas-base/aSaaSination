<?php

namespace Base\Contracts\Abstracts;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnonymousTransformerCollection extends AnonymousResourceCollection
{
    public function serialize()
    {
        return json_decode(json_encode($this->jsonSerialize()), true);
    }
}
