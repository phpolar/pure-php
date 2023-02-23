<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Generator;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
#[RunTestsInSeparateProcesses]
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

    public static function templateScenarios(): Generator
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

    #[Test]
    #[TestDox("Shall support using the public properties of an object as variables in a template")]
    #[DataProvider("templateScenarios")]
    public function criterion_1(string $pathToTemplate, string $expectedOutput)
    {
        $givenObject = $this->getContext();
        $templatingEngine = $this->getTemplatingEngine();
        $this->expectOutputString($expectedOutput);
        $templatingEngine->render($pathToTemplate, $givenObject);
    }
}
