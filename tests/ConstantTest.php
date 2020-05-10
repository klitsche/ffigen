<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Constant
 */
class ConstantTest extends TestCase
{
    public function testGetPhpCode(): void
    {
        $constant = new Constant('ANY', ['stringvalue', 123], 'some desc');
        $constant->addDocBlockTag('since', 'some version');

        $this->assertSame(
            <<<PHP
                /**
                 * some desc
                 * @since some version
                 */
                const ANY = [
                    'stringvalue',
                    123
                ];
            PHP,
            $constant->getPhpCode('    ')
        );
    }

    public function testGetName(): void
    {
        $constant = new Constant('ANY', 123);

        $this->assertSame('ANY', $constant->getName());
    }

    public function testGetValue(): void
    {
        $constant = new Constant('ANY', 'stringvalue');

        $this->assertSame('stringvalue', $constant->getValue());
    }

    public function testGetDocBlockTags(): void
    {
        $constant = new Constant('ANY', ['stringvalue', 123]);
        $constant->addDocBlockTag('since', 'some version');
        $constant->addDocBlockTag('since', 'some other');

        $this->assertSame(
            [
                ['since', 'some version'],
                ['since', 'some other'],
            ],
            $constant->getDocBlockTags()
        );
    }
}
