<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\NodeAbstract;

interface CompilerInterface
{
    public function compile(NodeAbstract $node): Type;

    public function resolveByName(string $name): ?Type;
}
