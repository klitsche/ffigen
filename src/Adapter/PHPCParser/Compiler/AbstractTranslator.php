<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\NodeAbstract;

abstract class AbstractTranslator implements TranslatorInterface
{
    protected CompilerInterface $compiler;

    public function setCompiler(CompilerInterface $compiler): void
    {
        $this->compiler = $compiler;
    }

    abstract public function matches(NodeAbstract $node): bool;

    abstract public function compile(NodeAbstract $node): Type;
}
