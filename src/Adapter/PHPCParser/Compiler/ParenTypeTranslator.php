<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\ParenType;
use PHPCParser\NodeAbstract;

class ParenTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof ParenType;
    }

    public function compile(NodeAbstract $node): Type
    {
        return $this->compiler->compile($node->parent);
    }
}
