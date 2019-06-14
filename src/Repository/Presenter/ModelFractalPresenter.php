<?php

namespace Repository\Presenter;

use Exception;
use Repository\Transformer\ModelTransformer;

class ModelFractalPresenter extends FractalPresenter
{
    /**
     * Transformer.
     *
     * @throws Exception
     * @return ModelTransformer
     */
    public function getTransformer()
    {
        if (! class_exists('League\Fractal\Manager')) {
            throw new Exception("Package required. Please install: 'composer require league/fractal' (0.12.*)");
        }

        return new ModelTransformer();
    }
}
