<?php

namespace Repository\Transformer;

use League\Fractal\TransformerAbstract;
use Repository\Contracts\Transformable;

class ModelTransformer extends TransformerAbstract
{
    public function transform(Transformable $model)
    {
        return $model->transform();
    }
}
