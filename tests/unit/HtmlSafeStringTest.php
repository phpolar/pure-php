<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpolar\PhpTemplating\HtmlSafeString
 */
final class HtmlSafeStringTest extends TestCase
{
    public function javascriptDirectives(): Generator
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

    // public function htmlEnts(): Generator
    // {
    //     foreach (
    //         get_html_translation_table(HTML_ENTITIES, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5) as $before => $after
    //     ) {
    //         yield [$before, $after];
    //     }
    // }

    /**
     * @testdox Shall not allow a uri that points to a file with a javascript file extension
     * @dataProvider javascriptDirectives()
     */
    public function test2(string $uri)
    {
        $sut = new HtmlSafeString($uri);
        $this->assertStringNotContainsString("javascript:", (string) $sut);
        $this->assertStringNotContainsString("JAVASCRIPT:", (string) $sut);
    }

    // /**
    //  * @testdox Shall not allow a uri that points to a file with a javascript file extension
    //  * @dataProvider htmlEnts()
    //  */
    // public function test3(string $before, string $after)
    // {
    //     $sut = new HtmlSafeString($before);
    //     $this->assertSame($after, (string) $sut);
    // }
}
