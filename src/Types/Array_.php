<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use FFI\CData;

class Array_ extends Type
{
    private Type $type;
    private ?int $size;

    public function __construct(Type $type, ?int $size = null)
    {
        $this->type = $type;
        $this->size = $size;
    }

    public function getCName(): string
    {
        return $this->type->getCName();
    }

    public function getName(): string
    {
        return $this->getDeclarationName() ?: $this->type->getName();
    }

    public function getCType(string $pointer = ''): string
    {
        if ($pointer === '') {
            return $this->type->getCType() . '[' . (string) $this->size . ']';
        }
        return $pointer . '(' . $this->type->getCType() . ')' . '[' . (string) $this->size . ']';
    }

    public function getPhpTypes(): string
    {
        return '?\\' . CData::class;
    }

    public function getPhpDocTypes(): string
    {
        return '\\' . CData::class . '|null';
    }
}
