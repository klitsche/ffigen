<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Array_;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\ArrayType;
use PHPCParser\NodeAbstract;

class ArrayTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof ArrayType;
    }

    public function compile(NodeAbstract $node): Type
    {
        if ($node instanceof ArrayType\ConstantArrayType) {
            return new Array_($this->compiler->compile($node->parent), (int) $node->size->value);
        }
        if ($node instanceof ArrayType\IncompleteArrayType) {
            return new Array_($this->compiler->compile($node->parent));
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported array type: %s, kind: %s', get_class($node), $node->kind));
    }
}
