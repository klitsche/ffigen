<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Config
 */
class ConfigTest extends TestCase
{
    public function testFromFile(): void
    {
        $config = COnfig::fromFile(__DIR__ . '/test.yml', __DIR__);

        $this->assertSame(__DIR__, $config->getOutputPath());
        $this->assertSame(['test.h'], $config->getHeaderFiles());
        $this->assertSame([], $config->getHeaderSearchPaths());
        $this->assertSame('Some\Parser', $config->getParserClass());
        $this->assertSame('Some\Generator', $config->getGeneratorClass());
        $this->assertSame(['/^AnyConstants/'], $config->getExcludeConstants());
        $this->assertSame(['/^AnyMethods/'], $config->getExcludeMethods());
        $this->assertSame('AnyLibrary', $config->getLibraryFile());
        $this->assertSame('AnyNamespace', $config->getNamespace());
    }
}
