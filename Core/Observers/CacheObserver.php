<?php

namespace Core\Observers;

use Core\Traits\Cacheable;
use Core\Abstracts\Observer;

class CacheObserver extends Observer
{
    /**
     * @param Cacheable | \Eloquent $model
     */
    public function created($model)
    {
        $model::cache()->store($model);
    }

    /**
     * @param Cacheable | \Eloquent $model
     */
    public function updated($model)
    {
        $model::cache()->store($model);
    }

    /**
     * @param Cacheable | \Eloquent $model
     */
    public function deleted($model)
    {
        $model::cache()->remove($model->getKey());
    }
}
