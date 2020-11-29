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
        $constant = new Constant('ANY', ['stringvalue', 123]);
        $this->assertSame(
            <<<PHPCODE
                const ANY = [
                    'stringvalue',
                    123
                ];
            PHPCODE,
            $constant->print('    ')
        );

        $constant->getDocBlock()->addTag(new DocBlockTag('since', 'some version'));
        $constant->getDocBlock()->setDescription('some desc');
        $this->assertSame(
            <<<PHPCODE
                /**
                 * some desc
                 * @since some version
                 */
                const ANY = [
                    'stringvalue',
                    123
                ];
            PHPCODE,
            $constant->print('    ')
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
}
