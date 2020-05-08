<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\Adapter\PHPCParser\Compiler\Compiler;
use Klitsche\FFIGen\Adapter\PHPCParser\Compiler\CompilerInterface;
use Klitsche\FFIGen\TypesCollection;
use PHPCParser\Node;

class TypesCollector
{
    private CompilerInterface $compiler;

    public function __construct(?CompilerInterface $compiler = null)
    {
        $this->compiler = $compiler ?? new Compiler();
    }

    /**
     * @param iterable|Node\Decl[] $declarations
     */
    public function collect(iterable $declarations): TypesCollection
    {
        $this->typeDefs = [];
        $types = [];

        foreach ($declarations as $declaration) {
            $types[] = $this->compiler->compile($declaration);
        }

        return new TypesCollection(...$types);
    }
}
