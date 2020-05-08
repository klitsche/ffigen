<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Decl\NamedDecl\TypeDecl\TypedefNameDecl\TypedefDecl;
use PHPCParser\NodeAbstract;

class TypeDefDeclTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof TypedefDecl;
    }

    public function compile(NodeAbstract $node): Type
    {
        return $this->compiler->compile($node->type)->withDeclarationName($node->name);
    }
}
