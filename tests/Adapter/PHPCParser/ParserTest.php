<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\Parser
 */
class ParserTest extends TestCase
{
    protected Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new Parser(
            new Config(
                [
                    'headerFiles' => [
                        dirname(__DIR__, 2) . '/test.h',
                    ],
                    'libraryFile' => 'any',
                    'namespace' => 'any',
                    'outputPath' => 'any',
                ]
            )
        );
    }

    public function testGetCDef(): void
    {
        $this->assertSame(
            <<<CDEF
            typedef int int_t;
            typedef float float_t;
            typedef char *char_t;
            typedef void *foo;
            typedef struct a a_t;
            typedef struct b {
              a_t *field1;
              a_t *field2;
            } b_t;
            typedef enum qux {
              QUUX,
              CORGE,
            } grault;
            typedef unsigned char bar[23];
            typedef long baz[][];
            typedef char *(*(**hairy[][8])())[];
            extern char **func1(char_t arg1, hairy arg2);
            const char *func2(const char *arg1, char *arg2);
            void func2(void);
            
            CDEF,
            $this->parser->getCDef()
        );
    }

    public function testGetDefines(): void
    {
        $this->assertCount(3, $this->parser->getDefines());
    }

    public function testGetTypes(): void
    {
        $this->assertCount(13, $this->parser->getTypes());
    }
}
