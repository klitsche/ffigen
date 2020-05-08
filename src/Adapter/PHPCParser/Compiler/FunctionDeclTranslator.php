<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Decl\NamedDecl\ValueDecl\DeclaratorDecl\FunctionDecl;
use PHPCParser\NodeAbstract;

class FunctionDeclTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof FunctionDecl;
    }

    public function compile(NodeAbstract $node): Type
    {
        return $this->compiler->compile($node->type)->withDeclarationName($node->name);
    }
}
