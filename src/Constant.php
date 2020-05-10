<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Brick\VarExporter\VarExporter;

class Constant
{
    private string $name;
    /**
     * @var int|string|array
     */
    private $value;
    private string $description;
    private array $docBlockTags;

    /**
     * @param int|string|array $value
     */
    public function __construct(string $name, $value, string $description = '')
    {
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
        $this->docBlockTags = [];
    }

    public function addDocBlockTag(string $name, string $text): void
    {
        $this->docBlockTags[] = [$name, $text];
    }

    /**
     * @return array [name, text]
     */
    public function getDocBlockTags(): array
    {
        return $this->docBlockTags;
    }

    public function getPhpCode(string $ident = ''): string
    {
        $template = <<<PHPCODE
        %s
        const %s = %s;
        PHPCODE;

        $code = sprintf($template, $this->getDocBlock(), $this->name, $this->getPhpValue());

        if ($ident !== '') {
            $parts = explode("\n", $code);
            $identParts = [];
            foreach ($parts as $part) {
                $identParts[] = $ident . rtrim($part);
            }
            $code = implode("\n", $identParts);
        }

        return $code;
    }

    public function getDocBlock(): string
    {
        $template = <<<PHPDOC
         /**%s
          */
         PHPDOC;

        $lines = [];
        if (empty($this->description) === false) {
            $lines[] = sprintf(' * %s', $this->description);
        }
        foreach ($this->docBlockTags as $tag) {
            $lines[] = sprintf(' * @%s %s', $tag[0], $tag[1]);
        }

        return sprintf($template, empty($lines) ? '' : "\n" . implode("\n", $lines));
    }

    private function getPhpValue(): string
    {
        return VarExporter::export($this->value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
