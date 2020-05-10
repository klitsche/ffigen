<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

abstract class Type
{
    protected string $cName;
    protected string $declarationName;
    protected bool $const = false;

    public function __construct(string $cName)
    {
        $this->cName = $cName;
    }

    public function hasCName(): bool
    {
        return isset($this->cName);
    }

    public function getCName(): string
    {
        return $this->cName;
    }

    public function getCType(string $pointer = ''): string
    {
        return ($this->const ? 'const ' : '') . $this->getName() . $pointer;
    }

    abstract public function getPhpTypes(): string;

    abstract public function getPhpDocTypes(): string;

    public function withConst(bool $flag)
    {
        $cloned = clone $this;
        $cloned->const = $flag;
        return $cloned;
    }

    public function isConst(): bool
    {
        return $this->const;
    }

    public function withDeclarationName(string $name)
    {
        $cloned = clone $this;
        $cloned->declarationName = $name;
        return $cloned;
    }

    public function hasDeclarationName(): bool
    {
        return isset($this->declarationName);
    }

    public function getDeclarationName(): string
    {
        return $this->declarationName ?? '';
    }

    public function getName(): string
    {
        return $this->getDeclarationName() ?: $this->getCName();
    }
}
