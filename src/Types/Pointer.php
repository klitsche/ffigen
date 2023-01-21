<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use FFI\CData;

class Pointer extends Type
{
    protected Type $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
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
        if (parent::isConst() && $this->type->isConst() === false) {
            $const = 'const ';
        } else {
            $const = '';
        }

        return $const . $this->type->getCType($pointer . '*');
    }

    public function getPhpTypes(): string
    {
        if ($this->type->getCName() === 'void') {
            // CData, object, string, or null
            return '';
        }
        return '?\\' . CData::class;
    }

    public function getPhpDocTypes(): string
    {
        if ($this->type->getCName() === 'void') {
            return '\\' . CData::class . '|object|string|null';
        }
        return '\\' . CData::class . '|null';
    }
}
