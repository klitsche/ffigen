<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\CharPointer;
use Klitsche\FFIGen\Types\Function_;
use Klitsche\FFIGen\Types\FunctionPointer;
use Klitsche\FFIGen\Types\Pointer;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\PointerType;
use PHPCParser\NodeAbstract;

class PointerTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof PointerType;
    }

    public function compile(NodeAbstract $node): Type
    {
        $compiledType = $this->compiler->compile($node->parent);
        if ($compiledType instanceof Function_) {
            return new FunctionPointer($compiledType);
        }
        if ($compiledType instanceof Builtin && $compiledType->getCName() === 'char') {
            return new CharPointer($compiledType);
        }
        return new Pointer($compiledType);
    }
}
