<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Type;

class MethodReturnParameter
{
    private ?Type $type;

    private string $description;

    public function __construct(?Type $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;
    }

    public function getPhpCode(): string
    {
        $type = $this->getPhpCodeType();
        if ($type === '') {
            return '';
        }
        return sprintf(': %s', $type);
    }

    public function getPhpCodeType(): string
    {
        return $this->type !== null
            ? $this->type->getPhpTypes()
            : '';
    }

    public function isVoid(): bool
    {
        return $this->type !== null && $this->type->getCName() === 'void';
    }

    public function getDocBlock(string $ident = ''): string
    {
        return sprintf('%s * @return %s %s', $ident, $this->getDocBlockType(), $this->description);
    }

    public function getDocBlockType(): string
    {
        return $this->type !== null
            ? $this->type->getPhpDocTypes()
            : 'mixed';
    }
}
