<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\ConfigInterface;
use Klitsche\FFIGen\DefinesCollection;
use Klitsche\FFIGen\ParserInterface;
use Klitsche\FFIGen\TypesCollection;
use PHPCParser\Context;
use PHPCParser\CParser;
use PHPCParser\Node\Decl;
use PHPCParser\Printer\C;

/**
 * Parser utilizing PHP CParser
 */
class Parser implements ParserInterface
{
    protected ConfigInterface $config;
    protected CParser $cparser;
    protected Context $context;
    /**
     * @var Decl[]
     */
    protected array $declarations;
    protected DefinesCollection $defines;
    protected TypesCollection $types;
    protected string $cDef;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->cparser = new CParser();
        $this->context = new Context($config->getHeaderSearchPaths());
    }

    protected function parse(): void
    {
        if (isset($this->defines)) {
            return;
        }

        $declarations = [];
        foreach ($this->config->getHeaderFiles() as $file) {
            $declarations = array_merge($declarations, $this->parseHeaderFile($file));
        }

        // collect defines
        $definesCollector = new DefinesCollector();
        $this->defines = $definesCollector->collect($this->cparser->getLastContext()->getDefines());

        // collect types
        $typesCollector = new TypesCollector();
        $this->types = $typesCollector->collect($declarations);

        // print cdef
        $printer = new C();
        $printer->omitConst = false;
        $this->cDef = $printer->printNodes($declarations, 0);
    }

    public function getDefines(): DefinesCollection
    {
        $this->parse();

        return $this->defines;
    }

    public function getTypes(): TypesCollection
    {
        $this->parse();
        return $this->types;
    }

    public function getCDef(): string
    {
        $this->parse();
        return $this->cDef;
    }

    protected function parseHeaderFile(string $file): array
    {
        $ast = $this->cparser->parse($file, $this->context);
        return $ast->declarations;
    }
}
