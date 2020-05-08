<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\TypedefType;
use PHPCParser\NodeAbstract;

class TypeDefTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof TypedefType;
    }

    public function compile(NodeAbstract $node): Type
    {
        if (Builtin::isMappable($node->name)) {
            return new Builtin($node->name);
        }

        $type = $this->compiler->resolveByName($node->name);
        if ($type !== null) {
            return $type;
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported type def type: %s, %s', get_class($node), $node->name));
    }
}
