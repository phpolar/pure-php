<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpolar\PhpTemplating\Binder
 * @uses \Phpolar\PhpTemplating\HtmlSafeContext
 * @uses \Phpolar\PhpTemplating\HtmlSafeString
 */
final class BinderTest extends TestCase
{
    /**
     * @testdox Shall bind a context to the given scope
     */
    public function test1()
    {
        $givenVar = "SOME VAR";
        $test = $this;
        $scope = function () use ($givenVar, $test) {
            $test->assertSame((string) $this->var, $givenVar);
        };
        $context = new HtmlSafeContext(new class ($givenVar) {
            public function __construct(public $var)
            {
            }
        });
        $sut = new Binder();
        $bound = $sut->bind($scope, $context);
        $bound();
    }
}