<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\ValueEvaluator
 */
class ValueEvaluatorTest extends TestCase
{
    public function testAnalyze(): void
    {
        $analyzer = new ValueEvaluator();

        $this->assertSame(2, $analyzer->evalulate('(1+1)'));
        $this->assertSame(-1, $analyzer->evalulate('((int)-1)'));
        $this->assertNull($analyzer->evalulate('int'));
    }
}
