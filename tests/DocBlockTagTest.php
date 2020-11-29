<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

class DocBlockTagTest extends TestCase
{
    public function testSetterAndGetter(): void
    {
        $tag = new DocBlockTag('any', 'text');

        $this->assertSame('any', $tag->getName());
        $this->assertSame('text', $tag->getValue());

        $tag->setName('other');
        $tag->setValue(null);

        $this->assertSame('other', $tag->getName());
        $this->assertSame(null, $tag->getValue());
    }

    public function testPrint(): void
    {
        $tag = new DocBlockTag('any', "text\nnewline");

        $this->assertSame("@any text\nnewline", $tag->print());

        $tag->setValue(null);

        $this->assertSame('@any', $tag->print());
    }
}
