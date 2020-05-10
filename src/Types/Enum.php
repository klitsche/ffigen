<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Types;

class Enum extends Type
{
    private array $values;

    public function __construct(string $cName)
    {
        parent::__construct($cName);
        $this->values = [];
    }

    public function getPhpTypes(): string
    {
        return 'int';
    }

    public function getPhpDocTypes(): string
    {
        return 'int';
    }

    public function getCType(string $pointer = ''): string
    {
        if ($this->hasDeclarationName()) {
            return parent::getCType($pointer);
        }
        return 'enum ' . parent::getCType($pointer);
    }

    /**
     * @param string|int|array|float|null $value
     */
    public function add(string $name, $value): self
    {
        $this->values[$name] = $value;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
