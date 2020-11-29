<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Pointer;
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
        $this->assertSame('return', $parameter->getDocBlockTag()->getName());
        $this->assertSame('float|null some desc', $parameter->getDocBlockTag()->getValue());
        $this->assertFalse($parameter->isVoid());
    }

    public function testGetterForVoid(): void
    {
        $parameter = new MethodReturnParameter(new Builtin('void'), 'some desc');

        $this->assertSame(': void', $parameter->getPhpCode());
        $this->assertSame('void', $parameter->getDocBlockType());
        $this->assertSame('void', $parameter->getPhpCodeType());
        $this->assertSame('return', $parameter->getDocBlockTag()->getName());
        $this->assertSame('void some desc', $parameter->getDocBlockTag()->getValue());
        $this->assertTrue($parameter->isVoid());
    }

    public function testGetterForVoidPointer(): void
    {
        $parameter = new MethodReturnParameter(new Pointer(new Builtin('void')), 'some desc');

        $this->assertSame('', $parameter->getPhpCode());
        $this->assertSame('\FFI\CData|object|string|null', $parameter->getDocBlockType());
        $this->assertSame('', $parameter->getPhpCodeType());
        $this->assertSame('return', $parameter->getDocBlockTag()->getName());
        $this->assertSame('\FFI\CData|object|string|null some desc', $parameter->getDocBlockTag()->getValue());
        $this->assertFalse($parameter->isVoid());
    }
}
