<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

interface ConfigInterface
{
    /**
     * @return array Paths tp header files relative to header search paths
     */
    public function getHeaderFiles(): array;

    /**
     * @return array Absolute paths to search for header files
     */
    public function getHeaderSearchPaths(): array;

    /**
     * @return string Name of library file.
     */
    public function getLibraryFile(): string;

    /**
     * @return string FQCN of Generator Class
     */
    public function getGeneratorClass(): string;

    /**
     * @return string FQCN of Parser Class
     */
    public function getParserClass(): string;

    /**
     * @return string Absolute or relative path to output directory.
     * Relative paths must be relative to the config file path.
     */
    public function getOutputPath(): string;

    /**
     * @return array Regular expressions to match constant names which should be excluded during generation of constants file.
     */
    public function getExcludeConstants(): array;

    /**
     * @return array Regular expressions to match methods names which should be excluded during generation of Method file.
     */
    public function getExcludeMethods(): array;

    /**
     * @return string Namespace for generated constants and Method trait
     */
    public function getNamespace(): string;
}
