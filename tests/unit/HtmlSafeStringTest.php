<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(HtmlSafeString::class)]
final class HtmlSafeStringTest extends TestCase
{
    public static function javascriptDirectives(): Generator
    {
        yield ["javascript:alert('hacked you!')"];
        yield ["javascript:alert('hacked you!');"];
        yield [" javascript:alert('hacked you!');"];
        yield ["/ javascript:alert('hacked you!');"];
        yield ["JAVASCRIPT:alert('hacked you!')"];
        yield ["JAVASCRIPT:alert('hacked you!');"];
        yield [" JAVASCRIPT:alert('hacked you!');"];
        yield ["/ JAVASCRIPT:alert('hacked you!');"];
    }

    public static function htmlEnts(): Generator
    {
        foreach (
            get_html_translation_table(HTML_ENTITIES, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5) as $before => $after
        ) {
            yield [$before, $after];
        }
    }

    #[TestDox("Shall not allow a uri that points to a file with a javascript file extension")]
    #[DataProvider("javascriptDirectives")]
    public function test2(string $uri)
    {
        $sut = new HtmlSafeString($uri);
        $this->assertStringNotContainsString("javascript:", (string) $sut);
        $this->assertStringNotContainsString("JAVASCRIPT:", (string) $sut);
    }

    #[TestDox("Shall not allow a uri that points to a file with a javascript file extension")]
    #[DataProvider("htmlEnts")]
    public function test3(string $before, string $after)
    {
        $sut = new HtmlSafeString($before);
        $this->assertSame($after, (string) $sut);
    }
}
