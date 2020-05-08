<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Function_;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\FunctionType;
use PHPCParser\NodeAbstract;

class FunctionTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof FunctionType;
    }

    public function compile(NodeAbstract $node): Type
    {
        if ($node instanceof FunctionType\FunctionProtoType) {
            $params = [];
            foreach ((array) $node->params as $i => $param) {
                $paramType = $this->compiler->compile($param);
                $params[$node->paramNames[$i] ?? 'arg' . $i] = $paramType;
            }
            return new Function_(
                $this->compiler->compile($node->return),
                $params,
                $node->isVariadic
            );
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported function type: %s, kind: %s', get_class($node), $node->kind));
    }
}
