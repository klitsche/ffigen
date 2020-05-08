<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

class FunctionPointer extends Pointer
{
    public function __construct(Function_ $type)
    {
        parent::__construct($type);
    }

    public function getCType(string $pointer = ''): string
    {
        return $this->type->getCType($pointer . '*');
    }

    public function getPhpTypes(): string
    {
        return $this->type->getPhpTypes();
    }

    public function getPhpDocTypes(): string
    {
        return $this->type->getPhpDocTypes();
    }
}
