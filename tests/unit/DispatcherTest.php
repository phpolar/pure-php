<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpolar\PhpTemplating\Dispatcher
 */
final class DispatcherTest extends TestCase
{
    /**
     * @testdox Shall call the given algorithm with the given path string
     */
    public function test1()
    {
        $givenPath = "SOME PATH";
        $algo = function (string $path) use ($givenPath) {
            $this->assertSame($path, $givenPath);
        };
        $sut = new Dispatcher();
        $sut->execute($algo, $givenPath);
    }
}