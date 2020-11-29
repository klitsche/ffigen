<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Type;

class MethodReturnParameter
{
    private ?Type $type;
    private DocBlockTag $docBlockTag;

    public function __construct(?Type $type, ?string $description = null)
    {
        $this->type = $type;
        $this->initDocBlockTag($description);
    }

    public function initDocBlockTag(?string $description): void
    {
        $this->docBlockTag = new DocBlockTag(
            'return',
            sprintf('%s %s', $this->getDocBlockType(), $description)
        );
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
