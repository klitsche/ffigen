<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class Method
{
    public string $template = <<<PHPCODE
        {{docblock}}public static function {{name}}({{params}}){{returntype}} 
        {
            {{returnstatement}}static::getFFI()->{{name}}({{varparams}});
        }
        PHPCODE;

    private string $name;
    /**
     * @var MethodParameter[]
     */
    private array $params;
    private ?MethodReturnParameter $return;
    private DocBlock $docBlock;

    /**
     * @param MethodParameter[] $params
     */
    public function __construct(string $name, array $params, ?MethodReturnParameter $return, ?string $description = null)
    {
        $this->name = $name;
        $this->params = $params;
        $this->return = $return;
        $this->initDocBlock($description);
    }

    private function initDocBlock(?string $description): void
    {
        $this->docBlock = new DocBlock();
        $this->docBlock->setDescription($description);

        if ($this->hasVoidParam() === false) {
            foreach ($this->params as $param) {
                $this->docBlock->addTag($param->getDocBlockTag());
            }
        }
        if ($this->return !== null && $this->return->isVoid() === false) {
            $this->docBlock->addTag($this->return->getDocBlockTag());
        }
    }

    public function print(string $ident = ''): string
    {
        $code = strtr(
            $this->template,
            [
                '{{docblock}}' => $this->docBlock->isEmpty() ? '' : $this->docBlock->print() . "\n",
                '{{name}}' => $this->name,
                '{{params}}' => $this->getPhpCodeParams(),
                '{{returntype}}' => $this->return->getPhpCode(),
                '{{returnstatement}}' => $this->return->isVoid() ? '' : 'return ',
                '{{varparams}}' => $this->getPhpVarParams(),
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

    private function getPhpCodeParams(): string
    {
        if ($this->hasVoidParam()) {
            return '';
        }
        return implode(
            ', ',
            array_map(fn (MethodParameter $param) => $param->getPhpCode(), $this->params),
        );
    }

    private function getPhpVarParams(): string
    {
        if ($this->hasVoidParam()) {
            return '';
        }
        return implode(
            ', ',
            array_map(fn (MethodParameter $param) => $param->getPhpVar(), $this->params),
        );
    }

    private function hasVoidParam(): bool
    {
        if (count($this->params) === 1 && ($this->params[0]->isVoid())) {
            return true;
        }

        return false;
    }

    public function getDocBlock(): DocBlock
    {
        return $this->docBlock;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
