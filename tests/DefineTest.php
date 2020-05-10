<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Define
 */
class DefineTest extends TestCase
{
    public function testConstruct(): void
    {
        $define = new Define('ANY', ['123', 456]);

        $this->assertSame('ANY', $define->getName());
        $this->assertSame(['123', 456], $define->getValue());
    }
}
