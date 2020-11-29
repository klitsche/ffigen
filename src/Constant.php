<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Brick\VarExporter\VarExporter;

class Constant
{
    public string $template = <<<PHPCODE
        {{docblock}}const {{name}} = {{value}};
        PHPCODE;

    private string $name;
    /**
     * @var int|string|array
     */
    private $value;
    private DocBlock $docBlock;

    /**
     * @param int|string|array $value
     */
    public function __construct(string $name, $value, ?string $description = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->initDocBlock($description);
    }

    private function initDocBlock(?string $description): void
    {
        $this->docBlock = new DocBlock();
        $this->docBlock->setDescription($description);
    }

    public function print(string $ident = ''): string
    {
        $code = strtr(
            $this->template,
            [
                '{{docblock}}' => $this->docBlock->isEmpty() ? '' : $this->docBlock->print() . "\n",
                '{{name}}' => $this->name,
                '{{value}}' => $this->getPhpValue(),
            ]
        );

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

    public function getDocBlock(): DocBlock
    {
        return $this->docBlock;
    }
}
