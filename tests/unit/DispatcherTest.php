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
        $algo = function (string $path) use ($givenPath): string|FileNotFound {
            $this->assertSame($path, $givenPath);
            return "";
        };
        $sut = new Dispatcher();
        $sut->getContents($algo, $givenPath);
    }

    /**
     * @testdox Shall call the given algorithm with the given path string
     */
    public function test2()
    {
        $givenPath = "SOME PATH";
        $algo = function (string $path) use ($givenPath): bool|FileNotFound {
            $this->assertSame($path, $givenPath);
            return false;
        };
        $sut = new Dispatcher();
        $sut->dispatch($algo, $givenPath);
    }
}