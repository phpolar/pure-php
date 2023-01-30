<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 */
final class SecurityTest extends TestCase
{
    public function getTemplatingEngine(): TemplateEngine
    {
        return new TemplateEngine(
            new FileRenderingStrategy(),
            new Binder(),
            new Dispatcher(),
        );
    }

    /**
     * @test
     * @testdox Shall mitigate cross-site scripting
     */
    public function criterion_1()
    {
        $templatingEngine = $this->getTemplatingEngine();
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
        $templatingEngine->render("tests/__templates__/hack.php", new HtmlSafeContext($objWithHacks));
    }
}
