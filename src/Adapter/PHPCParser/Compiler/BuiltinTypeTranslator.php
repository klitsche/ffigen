<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\BuiltinType;
use PHPCParser\NodeAbstract;

class BuiltinTypeTranslator extends AbstractTranslator
{
    public function setCompiler(CompilerInterface $compiler): void
    {
        $this->compiler = $compiler;
    }

    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof BuiltinType;
    }

    public function compile(NodeAbstract $node): Type
    {
        if (Builtin::isMappable($node->name)) {
            return new Builtin($node->name);
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported builtin type: %s, %s', get_class($node), $node->name));
    }
}
