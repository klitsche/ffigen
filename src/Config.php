<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Adapter\PHPCParser\Parser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser as YamlParser;

class Config implements ConfigInterface
{
    public const FILENAME = '.ffigen.yml';

    private array $headerFiles = [];
    private array $headerSearchPaths = [];
    private string $libraryFile = '';
    private string $generatorClass = Generator::class;
    private string $parserClass = Parser::class;
    private string $outputPath = '';
    private array $excludeConstants = ['/^_.*/'];
    private array $excludeMethods = [];
    private string $namespace = '';

    public function __construct(array $config)
    {
        // check required
        $missing = array_diff(['headerFiles', 'libraryFile', 'outputPath', 'namespace'], array_keys($config));
        if (empty($missing) === false) {
            throw new \InvalidArgumentException(sprintf('Missing required config parameter: %s', implode(', ', $missing)));
        }

        // map values
        foreach ($config as $parameter => $value) {
            if (property_exists($this, $parameter) === false) {
                throw new \InvalidArgumentException(sprintf('Unknown config parameter: %s', $parameter));
            }
            if ($value === null) {
                continue;
            }
            $this->{$parameter} = $value;
        }
    }

    /**
     * @param string $configYamlFile Path to config yaml file
     * @param string $basePath Usually the current working directory
     */
    public static function fromFile(string $configYamlFile, string $basePath): self
    {
        $filesystem = new Filesystem();
        if ($filesystem->isAbsolutePath($configYamlFile) === false) {
            $configYamlFile = $basePath . '/' . $configYamlFile;
        }
        if (is_dir($configYamlFile)) {
            $configYamlFile = rtrim($configYamlFile, '/') . '/' . self::FILENAME;
        }

        $parser = new YamlParser();
        $config = new static($parser->parseFile($configYamlFile));

        // handle relative output path to config file
        if ($filesystem->isAbsolutePath($config->outputPath) === false) {
            $config->outputPath = dirname($configYamlFile) . rtrim('/' . $config->outputPath, '/');
        }

        // handle relative header search paths
        foreach ($config->headerSearchPaths as $i => $searchPath) {
            if ($filesystem->isAbsolutePath($searchPath) === false) {
                $config->headerSearchPaths[$i] = dirname($configYamlFile) . rtrim('/' . $searchPath, '/');
            }
        }

        return $config;
    }

    public function getHeaderFiles(): array
    {
        return $this->headerFiles;
    }

    public function getHeaderSearchPaths(): array
    {
        return $this->headerSearchPaths;
    }

    public function getLibraryFile(): string
    {
        return $this->libraryFile;
    }

    public function getGeneratorClass(): string
    {
        return $this->generatorClass;
    }

    public function getParserClass(): string
    {
        return $this->parserClass;
    }

    public function getOutputPath(): string
    {
        return $this->outputPath;
    }

    public function getExcludeConstants(): array
    {
        return $this->excludeConstants;
    }

    public function getExcludeMethods(): array
    {
        return $this->excludeMethods;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }
}
