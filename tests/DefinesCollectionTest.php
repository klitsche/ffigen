<?php

declare(strict_types=1);

namespace Klitsche\FFIGen;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Klitsche\FFIGen\DefinesCollection
 */
class DefinesCollectionTest extends TestCase
{
    public function testGetIterator(): void
    {
        $collection = new DefinesCollection(
            new Define('ONE', 1),
            new Define('TWO', 2)
        );

        $defines = [];
        foreach ($collection as $define) {
            $defines[$define->getName()] = $define->getValue();
        }

        $this->assertSame(
            [
                'ONE' => 1,
                'TWO' => 2,
            ],
            $defines
        );
    }

    public function testCount(): void
    {
        $collection = new DefinesCollection(
            new Define('ONE', 1),
            new Define('TWO', 2)
        );

        $this->assertCount(2, $collection);
    }
}
