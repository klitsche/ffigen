<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Struct;
use Klitsche\FFIGen\Types\Type;
use PHPCParser\Node\Decl\NamedDecl\TypeDecl\TagDecl\RecordDecl;
use PHPCParser\NodeAbstract;

class RecordDeclTranslator extends AbstractTranslator
{
    public function matches(NodeAbstract $node): bool
    {
        return $node instanceof RecordDecl;
    }

    public function compile(NodeAbstract $node): Type
    {
        $struct = new Struct(
            $node->name ?: '',
            $node->kind === RecordDecl::KIND_UNION
        );

        foreach ((array) $node->fields as $field) {
            $struct->add($field->name, $this->compiler->compile($field->type));
        }

        return $struct;
    }
}
