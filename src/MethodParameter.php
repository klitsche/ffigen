<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Type;

class MethodParameter
{
    private ?Type $type;
    private string $name;
    private string $description;
    private bool $isVariadic;

    public function __construct(?Type $type, string $name, string $description, bool $isVariadic = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->isVariadic = $isVariadic;
    }

    public function getPhpCode(): string
    {
        return trim(sprintf('%s %s', $this->getPhpCodeType(), $this->getPhpVar()));
    }

    public function getPhpCodeType(): string
    {
        return $this->type !== null
            ? $this->type->getPhpTypes()
            : '';
    }

    public function getPhpVar(): string
    {
        return sprintf('%s$%s', $this->isVariadic ? '...' : '', $this->name);
    }

    public function isVoid(): bool
    {
        return $this->type !== null
            && $this->type->getCType() === 'void';
    }

    public function getDocBlock(string $ident = ''): string
    {
        return sprintf('%s * @param %s %s %s', $ident, $this->getDocBlockType(), $this->getPhpVar(), $this->description);
    }

    public function getDocBlockType(): string
    {
        return $this->type !== null
            ? $this->type->getPhpDocTypes()
            : 'mixed';
    }
}
