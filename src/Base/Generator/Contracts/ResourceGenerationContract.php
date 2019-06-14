<?php

namespace Base\Generator\Contracts;

use Base\Generator\Support\Stub;

interface ResourceGenerationContract
{
    /**
     * @return string
     */
    public function getFilePath(): string;

    /**
     * @return Stub
     */
    public function getStub(): Stub;

    /**
     * @return string|null
     */
    public function getNamespace(): ?string;

    /**
     * @return string|null
     */
    public function getClassName(): ?string;

    /**
     * @return string|null
     */
    public function getModuleName(): string;
}
