<?php

namespace Base\Traits;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait HandlesNullResource
{
    public function exists($model)
    {
        if ($model === null) {
            throw new NotFoundHttpException('Could not found resource.');
        }
    }
}
