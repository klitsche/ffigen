<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use FFI\CData;

class Struct extends Type
{
    private array $layout;
    private bool $isUnion;

    public function __construct(string $cName, bool $isUnion)
    {
        parent::__construct($cName);
        $this->isUnion = $isUnion;
        $this->layout = [];
    }

    public function getCType(string $pointer = ''): string
    {
        if ($this->hasDeclarationName()) {
            return parent::getCType($pointer);
        }
        return ($this->isUnion ? 'union ' : 'struct ') . parent::getCType($pointer);
    }

    public function getPhpTypes(): string
    {
        return '?\\' . CData::class;
    }

    public function getPhpDocTypes(): string
    {
        return '\\' . CData::class . '|null';
    }

    public function add(string $name, Type $type): self
    {
        $this->layout[$name] = $type;
        return $this;
    }

    public function getLayout(): array
    {
        return $this->layout;
    }

    public function isUnion(): bool
    {
        return $this->isUnion;
    }
}
