<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 */
final class TemplatingTest extends TestCase
{
    const TITLE = "TEST TITLE";
    const NAME = "Some Name";
    const NUM = PHP_INT_MAX;

    public function getTemplatingEngine(): TemplateEngine
    {
        return new TemplateEngine(
            new FileRenderingStrategy(),
            new Binder(),
            new Dispatcher(),
        );
    }

    public function getContext(): HtmlSafeContext
    {
        return new HtmlSafeContext(new class() {
            public string $title = TemplatingTest::TITLE;
            public string $name = TemplatingTest::NAME;
            public int $num = TemplatingTest::NUM;
        });
    }

    public function templateScenarios(): Generator
    {
        $title = self::TITLE;
        $name = self::NAME;
        $num = self::NUM;
        yield [
            "tests/__templates__/a.php",
            <<<HTML
            <h1>{$title}</h1>
            HTML,
        ];
        yield [
            "tests/__templates__/b.php",
            <<<HTML
            <p>{$name}</p>
            HTML,
        ];
        yield [
            "tests/__templates__/c.php",
            <<<HTML
            <div>{$num}</div>
            HTML,
        ];
    }

    /**
     * @testdox Shall support using the public properties of an object as variables in a template
     * @test
     * @dataProvider templateScenarios()
     */
    public function criterion_1(string $pathToTemplate, string $expectedOutput)
    {
        $givenObject = $this->getContext();
        $templatingEngine = $this->getTemplatingEngine();
        $this->expectOutputString($expectedOutput);
        $templatingEngine->render($pathToTemplate, $givenObject);
    }
}
