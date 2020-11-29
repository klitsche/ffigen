<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class DocBlockTag
{
    public string $template = <<<PHPDOC
        @%s %s
        PHPDOC;
    private string $name;
    private ?string $value;

    public function __construct(string $name, ?string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function print(): string
    {
        $phpdoc = sprintf($this->template, $this->name, $this->value ?: '');
        if ($this->value === null) {
            $phpdoc = rtrim($phpdoc);
        }
        return $phpdoc;
    }
}
