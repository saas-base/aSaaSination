<?php

namespace Base\Core;

final class File
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * ApiModule constructor.
     * @param $name
     */
    public function __construct(string $name, string $path, Resource $resource)
    {
        $this->name     = $name;
        $this->path     = $path;
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->resource->getNamespace().'\\'.$this->getName();
    }

    public function getFileName(): string
    {
        return $this->getName().'.php';
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->resource->getModule();
    }
}
