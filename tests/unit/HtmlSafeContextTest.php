<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @covers \Phpolar\PhpTemplating\HtmlSafeContext
 * @uses \Phpolar\PhpTemplating\HtmlSafeString
 */
final class HtmlSafeContextTest extends TestCase
{
    /**
     * @var resource
     */
    protected $res;

    public function tearDown(): void
    {
        if (is_resource($this->res) === true) {
            fclose($this->res);
        }
    }

    /**
     * @testdox Shall convert properties of type string to HtmlSafeString
     */
    public function test1()
    {
        $obj = new class() {
            public string $str = "what";
        };
        $sut = new HtmlSafeContext($obj);
        $this->assertInstanceOf(HtmlSafeString::class, $sut->str);
    }

    /**
     * @testdox Shall convert Stringable properties to HtmlSafeString
     */
    public function test2()
    {
        $strable = new class() implements Stringable {
            public function __toString(): string
            {
                return "a string";
            }
        };
        $obj = new class() {
            public Stringable $strable;
        };
        $obj->strable = $strable;
        $sut = new HtmlSafeContext($obj);
        $this->assertInstanceOf(HtmlSafeString::class, $sut->strable);
    }

    /**
     * @testdox Shall not convert non-string non-Stringable properties to HtmlSafeString
     */
    public function test3()
    {
        $obj = new class() {
            public int $i = PHP_INT_MIN;
            public bool $b = false;
            public float $f = PHP_FLOAT_MIN;
            public ?float $nope = null;
        };
        $sut = new HtmlSafeContext($obj);
        foreach ($sut as $val) {
            $this->assertNotInstanceOf(HtmlSafeString::class, $val);
        }
    }

    /**
     * @testdox Shall convert string and Stringable items in array properties to HtmlSafeString
     */
    public function test4()
    {
        $strable = new class() implements Stringable {
            public function __toString(): string
            {
                return "a string";
            }
        };
        $obj = new class() {
            public array $arr;
        };
        $obj->arr = ["my str", $strable, ["another string", ["nested again"]]];
        $sut = new HtmlSafeContext($obj);
        array_walk_recursive(
            $sut->arr,
            fn ($val) => $this->assertInstanceOf(HtmlSafeString::class, $val),
        );
    }

    /**
     * @testdox Shall convert string and Stringable properties in object properties to HtmlSafeString
     */
    public function test5()
    {
        $strable = new class() implements Stringable {
            public function __toString(): string
            {
                return "a string";
            }
        };
        $obj = new class() {
            public object $obj;
        };
        $obj->obj = (object) [
            "prop1" => "my str",
            "prop2" => $strable,
            "prop3" => (object) ["a" => "another string", "b" => (object) ["z" => "nested again"]],
        ];
        $sut = new HtmlSafeContext($obj);
        array_walk_recursive(
            $sut->obj,
            fn ($val) => $val instanceof \stdClass ? array_walk_recursive(
                    $val,
                    fn ($v) => $v instanceof \stdClass ? array_walk_recursive(
                            $v,
                            fn ($vv) => $this->assertInstanceOf(HtmlSafeString::class, $vv)
                        ) : $this->assertInstanceOf(HtmlSafeString::class, $v),
                ) : $this->assertInstanceOf(HtmlSafeString::class, $val),
        );
    }


    /**
     * @testdox Shall not convert non-string non-Stringable properties to HtmlSafeString
     */
    public function test6()
    {
        $obj = new class() {
            /**
             * @var resource
             */
            public $r1;
            /**
             * @var resource
             */
            public $r2;
        };
        $obj->r1 = fopen("php://memory", "r");
        $res = fopen("php://memory", "r");
        $obj->r2 = $res;
        fclose($res);
        $sut = new HtmlSafeContext($obj);
        foreach ($sut as $val) {
            $this->assertEmpty($val);
        }
    }
}
