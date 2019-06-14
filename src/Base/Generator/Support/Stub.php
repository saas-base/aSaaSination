<?php

namespace Base\Generator\Support;

class Stub extends \Nwidart\Modules\Support\Stub
{
    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath()
    {
        return get_base_path() . '/Generator/Stubs/' . $this->path;
    }

    public function getName()
    {
        return $this->path;
    }

    public function getOptions() :array
    {
        return $this->getReplaces();
    }
}
