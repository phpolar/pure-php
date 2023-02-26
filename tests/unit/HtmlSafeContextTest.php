<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Stringable;

#[CoversClass(HtmlSafeContext::class)]
#[UsesClass(HtmlSafeString::class)]
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

    #[TestDox("Shall convert properties of type string to HtmlSafeString")]
    public function tes1()
    {
        $obj = new class() {
            public string $str = "what";
        };
        $sut = new HtmlSafeContext($obj);
        $this->assertInstanceOf(HtmlSafeString::class, $sut->str);
    }

    #[TestDox("Shall convert Stringable properties to HtmlSafeString")]
    public function tes2()
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

    #[TestDox("Shall not convert non-string non-Stringable properties to HtmlSafeString")]
    public function test3()
    {
        $obj = new class() {
            public int $i = PHP_INT_MIN;
            public bool $b = false;
            public float $f = PHP_FLOAT_MIN;
            public ?float $nope = null;
        };
        $sut = new HtmlSafeContext($obj);
        foreach ($obj as $val) {
            $this->assertThat($val, $this->logicalNot($this->isTrue(is_string($sut->$val))));
        }
    }

    #[TestDox("Shall convert string and Stringable items in array properties to HtmlSafeString")]
    public function tes4()
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

    #[TestDox("Shall convert string and Stringable properties in object properties to HtmlSafeString")]
    public function tes5()
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


    #[TestDox("Shall not convert non-string non-Stringable properties to HtmlSafeString")]
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
        foreach ($obj as $val) {
            $this->assertEmpty($sut->$val);
        }
    }

    #[TestDox("Shall return null when property does not exist on inner object")]
    public function test7()
    {
        $obj = new class() {
            public $name = self::class;
        };
        $sut = new HtmlSafeContext($obj);
        foreach (["non", "existing", "props"] as $val) {
            $this->assertNull($sut->$val);
        }
    }
}
