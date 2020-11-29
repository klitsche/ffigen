<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Type;

class MethodParameter
{
    private ?Type $type;
    private string $name;
    private bool $isVariadic;
    private DocBlockTag $docBlockTag;

    public function __construct(?Type $type, string $name, ?string $description = null, bool $isVariadic = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->isVariadic = $isVariadic;
        $this->initDocBlockTag($description);
    }

    public function initDocBlockTag(?string $description): void
    {
        $this->docBlockTag = new DocBlockTag(
            'param',
            sprintf('%s %s %s', $this->getDocBlockType(), $this->getPhpVar(), $description)
        );
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

    public function getDocBlockTag(): DocBlockTag
    {
        return $this->docBlockTag;
    }

    public function getDocBlockType(): string
    {
        return $this->type !== null
            ? $this->type->getPhpDocTypes()
            : 'mixed';
    }
}
