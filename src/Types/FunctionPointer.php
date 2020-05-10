<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

class FunctionPointer extends Pointer
{
    public function __construct(Function_ $type)
    {
        parent::__construct($type);
    }

    public function hasDeclarationName(): bool
    {
        return parent::hasDeclarationName() || $this->type->hasDeclarationName();
    }

    public function getDeclarationName(): string
    {
        return parent::getDeclarationName() ?: $this->type->getDeclarationName();
    }

    public function getCType(string $pointer = ''): string
    {
        $type = $this->type;
        if ($this->hasDeclarationName()) {
            $type = $type->withDeclarationName($this->getDeclarationName());
        }

        return $type->getCType($pointer . '*');
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
