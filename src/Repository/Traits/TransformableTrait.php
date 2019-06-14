<?php

namespace Repository\Traits;

trait TransformableTrait
{
    /**
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}
