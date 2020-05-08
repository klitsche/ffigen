<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser\Compiler;

use Klitsche\FFIGen\Types\Type;
use PHPCParser\NodeAbstract;

class Compiler implements CompilerInterface
{
    /**
     * @var TranslatorInterface[]
     */
    private array $translators;

    /**
     * @var Type[]
     */
    private array $typeDefs;

    /**
     * @param TranslatorInterface[] $translators
     */
    public function __construct(array $translators = [])
    {
        if (empty($translators)) {
            $translators = [
                new ArrayTypeTranslator(),
                new AttributedTypeTranslator(),
                new BuiltinTypeTranslator(),
                new EnumDeclTranslator(),
                new FunctionDeclTranslator(),
                new FunctionTypeTranslator(),
                new ParenTypeTranslator(),
                new PointerTypeTranslator(),
                new RecordDeclTranslator(),
                new TagTypeTranslator(),
                new TypeDefDeclTranslator(),
                new TypeDefTypeTranslator(),
            ];
        }

        $this->translators = [];
        foreach ($translators as $translator) {
            $translator->setCompiler($this);
            $this->translators[] = $translator;
        }
    }

    public function compile(NodeAbstract $node): Type
    {
        $translators = $this->translators;
        foreach ($translators as $translator) {
            if ($translator->matches($node)) {
                $type = $translator->compile($node);
                $type = $this->resolveTypeDefs($type);
                if ($type->hasDeclarationName()) {
                    $this->typeDefs[$type->getDeclarationName()] = $type;
                }
                return $type;
            }
        }

        var_dump($node);
        throw new \LogicException(sprintf('No translator for type %s', get_class($node)));
    }

    /**
     * - dec:a_t => cname:a
     * - dec:b_t => cname a_t
     * - cname:b_t
     * > cname:b_t > dec:b_t > cname:a_t => dec:a_t => cname:a
     */
    private function resolveTypeDefs(Type $type): Type
    {
        if ($type->hasCName()
            && isset($this->typeDefs[$type->getCName()])
            && $this->typeDefs[$type->getCName()] !== $type
        ) {
            return $this->resolveTypeDefs($this->typeDefs[$type->getCName()]);
        }

        return $type;
    }

    public function resolveByName(string $name): ?Type
    {
        return $this->typeDefs[$name] ?? null;
    }
}
