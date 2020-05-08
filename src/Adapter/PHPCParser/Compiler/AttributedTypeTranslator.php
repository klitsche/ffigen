<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\AttributedType;
use PHPCParser\NodeAbstract;

class AttributedTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof AttributedType;
    }

    public function compile(NodeAbstract $node): Type
    {
        $compiledType = $this->compiler->compile($node->parent);
        if ($node->kind === AttributedType::KIND_CONST) {
            return $compiledType->withConst(true);
        } elseif ($node->kind === AttributedType::KIND_EXTERN) {
            return $compiledType;
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported attributed type: %s, kind: %s', get_class($type), $type->kind));
    }
}
