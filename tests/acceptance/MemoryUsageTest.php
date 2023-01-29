<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;
use const \Phpolar\PhpTemplating\Tests\PROJECT_MEMORY_USAGE_THRESHOLD;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 */
final class MemoryUsageTest extends TestCase
{
    public function thresholds()
    {
        return [
            [(int) PROJECT_MEMORY_USAGE_THRESHOLD]
        ];
    }

    /**
     * @test
     * @dataProvider thresholds()
     * @testdox Memory usage shall be below $threshold bytes
     */
    public function shallBeBelowThreshold(int $threshold)
    {
        $engine = new TemplateEngine(
            new FileRenderer(),
            new Binder(),
            new Dispatcher(),
        );
        $objWithHacks = new class() {
            public string $hack1 = "<script>alert('hacked');</script>";
            public string $directiveHack1 = "javascript:alert('hacked');";
            public string $directiveHack2 = "# javascript:alert('hacked');";
            public string $directiveHack3 = "/ javascript:alert('hacked');";
        };
        $mitigated = <<<HTML
        &lt;script&gt;alert&lpar;&apos;hacked&apos;&rpar;&semi;&lt;&sol;script&gt;
        <a alert&lpar;&apos;hacked&apos;&rpar;&semi;>HACK</a>
        <a href=&num; alert&lpar;&apos;hacked&apos;&rpar;&semi;>HACK</a>
        <img src=&sol; alert&lpar;&apos;hacked&apos;&rpar;&semi; />
        HTML;
        $this->expectOutputString($mitigated);
        $totalUsed = -memory_get_usage();
        $engine->render("tests/__templates__/hack.php", new HtmlSafeContext($objWithHacks));
        $totalUsed += memory_get_usage();
        $this->assertGreaterThan(0, $totalUsed);
        $this->assertLessThanOrEqual($threshold, $totalUsed);
    }
}
