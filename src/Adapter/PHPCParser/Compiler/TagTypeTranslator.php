<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Type\TagType;
use PHPCParser\NodeAbstract;

class TagTypeTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof TagType;
    }

    public function compile(NodeAbstract $node): Type
    {
        if ($node instanceof TagType\RecordType) {
            return $this->compiler->compile($node->decl);
        }
        if ($node instanceof TagType\EnumType) {
            return $this->compiler->compile($node->decl);
        }

        var_dump($node);
        throw new \LogicException(sprintf('Unsupported tag type: %s, kind: %s', get_class($node), $node->kind));
    }
}
