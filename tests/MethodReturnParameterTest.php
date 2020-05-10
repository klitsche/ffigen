<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodReturnParameter
 */
class MethodReturnParameterTest extends TestCase
{
    public function testGetterForNonVoid(): void
    {
        $parameter = new MethodReturnParameter(new Builtin('float'), 'some desc');

        $this->assertSame(': ?float', $parameter->getPhpCode());
        $this->assertSame('float|null', $parameter->getDocBlockType());
        $this->assertSame('?float', $parameter->getPhpCodeType());
        $this->assertSame('     * @return float|null some desc', $parameter->getDocBlock('    '));
        $this->assertFalse($parameter->isVoid());
    }

    public function testGetterForVoid(): void
    {
        $parameter = new MethodReturnParameter(new Builtin('void'), 'some desc');

        $this->assertSame(': void', $parameter->getPhpCode());
        $this->assertSame('void', $parameter->getDocBlockType());
        $this->assertSame('void', $parameter->getPhpCodeType());
        $this->assertSame('     * @return void some desc', $parameter->getDocBlock('    '));
        $this->assertTrue($parameter->isVoid());
    }
}
