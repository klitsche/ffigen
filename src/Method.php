<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

class Method
{
    private string $name;

    private string $description;

    private array $docBlockTags;
    /**
     * @var MethodParameter[]
     */
    private array $params;

    private ?MethodParameter $return;

    public function __construct(string $name, array $params, ?MethodParameter $return, string $description)
    {
        $this->name = $name;
        $this->params = $params;
        $this->return = $return;
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
        public static function %s(%s)%s 
        {
            %sstatic::getFFI()->%s(%s);
        }
        PHPCODE;

        $code = sprintf(
            $template,
            $this->getDocBlock(),
            $this->name,
            $this->getPhpCodeParams($this->params),
            $this->return->getPhpCode(),
            $this->return->isVoid() ? '' : 'return ',
            $this->name,
            $this->getPhpVarParams($this->params),
        );

        if ($ident !== '') {
            $parts = explode("\n", $code);
            $code = '';
            foreach ($parts as $part) {
                $code .= $ident . $part . "\n";
            }
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
        if ($this->hasVoidParam() === false) {
            foreach ($this->params as $param) {
                $lines[] = $param->getDocBlock();
            }
        }
        if ($this->return->isVoid() === false) {
            $lines[] = $this->return->getDocBlock();
        }

        return sprintf($template, empty($lines) ? '' : "\n" . implode("\n", $lines));
    }


    public function getName(): string
    {
        return $this->name;
    }
}
