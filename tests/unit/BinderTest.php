<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Binder::class)]
#[UsesClass(HtmlSafeContext::class)]
#[UsesClass(HtmlSafeString::class)]
final class BinderTest extends TestCase
{
     #[TestDox("Shall bind a context to the given scope")]
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
