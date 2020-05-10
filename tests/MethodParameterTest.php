<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use Klitsche\FFIGen\Types\Builtin;
use Klitsche\FFIGen\Types\Pointer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\MethodParameter
 */
class MethodParameterTest extends TestCase
{
    public function testGetterForNonVoid(): void
    {
        $parameter = new MethodParameter(new Builtin('int'), 'param1', 'some desc');

        $this->assertSame(
            '?int $param1',
            $parameter->getPhpCode()
        );
        $this->assertSame('$param1', $parameter->getPhpVar());
        $this->assertSame('int|null', $parameter->getDocBlockType());
        $this->assertSame('?int', $parameter->getPhpCodeType());
        $this->assertSame('     * @param int|null $param1 some desc', $parameter->getDocBlock('    '));
        $this->assertFalse($parameter->isVoid());
    }

    public function testGetterForVoid(): void
    {
        $parameter = new MethodParameter(new Builtin('void'), 'param1', 'some desc');

        $this->assertTrue($parameter->isVoid());
    }

    public function testGetterForVoidPointer(): void
    {
        $parameter = new MethodParameter(new Pointer(new Builtin('void')), 'param1', 'some desc');

        $this->assertSame('$param1', $parameter->getPhpVar());
        $this->assertSame('\FFI\CData|object|string|null', $parameter->getDocBlockType());
        $this->assertSame('', $parameter->getPhpCodeType());
        $this->assertSame('     * @param \FFI\CData|object|string|null $param1 some desc', $parameter->getDocBlock('    '));
        $this->assertFalse($parameter->isVoid());
    }
}
