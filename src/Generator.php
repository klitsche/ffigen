<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Symfony\Component\Filesystem\Filesystem;

class Generator implements GeneratorInterface
{
    private ConfigInterface $config;
    private ParserInterface $parser;

    public function __construct(ConfigInterface $config, ParserInterface $parser)
    {
        $this->config = $config;
        $this->parser = $parser;
    }

    public function generate(): void
    {
        $defines = $this->parser->getDefines();
        $types = $this->parser->getTypes();

        $constants = new ConstantsCollection(
            $defines,
            $types,
            $this->config->getExcludeConstants()
        );
        $constants->add(new Constant('FFI_CDEF', $this->parser->getCDef(), implode(', ', $this->config->getHeaderFiles())));
        $constants->add(new Constant('FFI_LIB', $this->config->getLibraryFile(), 'c library file name'));

        $filesystem = new Filesystem();

        $constantsPrinter = new ConstantsPrinter($constants);
        $filesystem->dumpFile(
            $this->config->getOutputPath() . '/constants.php',
            $constantsPrinter->print($this->config->getNamespace())
        );

        $methods = new MethodsCollection($types, $this->config->getExcludeMethods());
        $methodsPrinter = new MethodsPrinter($methods);
        $filesystem->dumpFile(
            $this->config->getOutputPath() . '/Methods.php',
            $methodsPrinter->print($this->config->getNamespace())
        );
    }
}
