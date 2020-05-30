<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Symfony\Component\Filesystem\Filesystem;

class Generator implements GeneratorInterface
{
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function generate(): void
    {
        $parser = $this->createParser();

        $defines = $parser->getDefines();
        $types = $parser->getTypes();

        $constantsCollector = new ConstantsCollector();
        $constants = $constantsCollector->collect(
            $defines,
            $types,
        );
        $constants->add(new Constant('FFI_CDEF', $parser->getCDef(), implode(', ', $this->config->getHeaderFiles())));
        $constants->add(new Constant('FFI_LIB', $this->config->getLibraryFile(), 'c library file name'));

        $filesystem = new Filesystem();

        $constantsPrinter = new ConstantsPrinter($constants->filter($this->config->getExcludeConstants()));
        $filesystem->dumpFile(
            $this->config->getOutputPath() . '/constants.php',
            $constantsPrinter->print($this->config->getNamespace())
        );

        $methodsCollector = new MethodsCollector();
        $methods = $methodsCollector->collect($types);
        $methodsPrinter = new MethodsPrinter($methods->filter($this->config->getExcludeMethods()));
        $filesystem->dumpFile(
            $this->config->getOutputPath() . '/Methods.php',
            $methodsPrinter->print($this->config->getNamespace())
        );
    }

    protected function createParser(): ParserInterface
    {
        $parserClass = $this->config->getParserClass();
        return new $parserClass($this->config);
    }
}
