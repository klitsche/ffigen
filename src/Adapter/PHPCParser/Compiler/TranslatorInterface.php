<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\NodeAbstract;

interface TranslatorInterface
{
    public function setCompiler(CompilerInterface $compiler): void;

    public function matches(NodeAbstract $node): bool;

    public function compile(NodeAbstract $node): Type;
}
