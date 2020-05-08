<?php

declare(strict_types=1);

namespace Klitsche\FFIGen\Adapter\PHPCParser;

use Klitsche\FFIGen\Define;
use Klitsche\FFIGen\DefinesCollection;
use PHPCParser\CParser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\Adapter\PHPCParser\DefinesCollector
 * @covers \Klitsche\FFIGen\DefinesCollection
 * @covers \Klitsche\FFIGen\Define
 */
class DefinesCollectorTest extends TestCase
{
    public function testCollect(): void
    {
        $parser = new CParser();
        $parser->parse(dirname(__DIR__, 2) . '/test.h');

        $collector = new DefinesCollector();
        $collection = $collector->collect($parser->getLastContext()->getDefines());

        $this->assertSame(3, iterator_count($collection));
        $this->assertEquals(
            new DefinesCollection(
                new Define('ONE', 1),
                new Define('TWO', 2),
                new Define('THREE', -1),
            ),
            $collection
        );
    }
}
