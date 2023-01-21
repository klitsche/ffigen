<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

use FFI\CData;

/**
 * Handle string
 * - Immutable: char* = use CData
 * - Mutable: const char* = use php string
 */
class CharPointer extends Pointer
{
    public function __construct(Builtin $type)
    {
        parent::__construct($type);
    }

    public function getCName(): string
    {
        return $this->type->getCName();
    }

    public function getPhpTypes(): string
    {
        if ($this->isConst()) {
            return '?string';
        }

        return '?\\' . CData::class;
    }

    public function getPhpDocTypes(): string
    {
        if ($this->isConst()) {
            return 'string|null';
        }

        return '\\' . CData::class . '|null';
    }

    public function isConst(): bool
    {
        return parent::isConst() || $this->type->isConst();
    }
}
