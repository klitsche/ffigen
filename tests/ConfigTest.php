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

        $this->assertSame(__DIR__ . '/temp', $config->getOutputPath());
        $this->assertSame(['test.h'], $config->getHeaderFiles());
        $this->assertSame([], $config->getHeaderSearchPaths());
        $this->assertSame('Some\Parser', $config->getParserClass());
        $this->assertSame('Some\Generator', $config->getGeneratorClass());
        $this->assertSame(['/^AnyConstants/'], $config->getExcludeConstants());
        $this->assertSame(['/^AnyMethods/'], $config->getExcludeMethods());
        $this->assertSame('AnyLibrary', $config->getLibraryFile());
        $this->assertSame('AnyNamespace', $config->getNamespace());
    }

    public function testRequiredParameters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/headerFiles/');
        $this->expectExceptionMessageMatches('/libraryFile/');
        $this->expectExceptionMessageMatches('/outputPath/');
        $this->expectExceptionMessageMatches('/namespace/');

        new Config([]);
    }

    public function testUnknownParameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/anyOther/');

        new Config(
            [
                'anyOther' => 'value',
                'headerFiles' => [],
                'libraryFile' => 'anylib',
                'outputPath' => '',
                'namespace' => 'Any',
            ]
        );
    }
}
