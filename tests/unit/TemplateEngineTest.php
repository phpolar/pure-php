<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Closure;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;
use stdClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(TemplateEngine::class)]
#[UsesClass(HtmlSafeContext::class)]
#[UsesClass(HtmlSafeString::class)]
#[UsesClass(StreamContentStrategy::class)]
final class TemplateEngineTest extends TestCase
{
    const EXISTING_FILE = "tests/__templates__/a.php";
    const FAKE_CONTENTS = "some string";

    public static function resolvedPaths(): Generator
    {
        yield ["tpl1", "src/templates/tpl1.phtml"];
        yield ["tpl2", "src/templates/tpl2.php"];
        yield ["tpl3", "src/templates/tpl3.html"];
    }

    #[TestDox("Shall display content from given template with object variables bound")]
    public function test1()
    {
        $obj = new stdClass();
        $algo = fn (): bool|FileNotFound => true;
        $renderingAlgoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn($algo);
        $executorSpy->expects($this->once())->method("dispatch")->willReturn($algo());
        $template = new TemplateEngine(
            $renderingAlgoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->render(self::EXISTING_FILE, new HtmlSafeContext($obj));
    }

    #[TestDox("Shall not execute rendering algorithm when binding fails")]
    public function test2()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound => "";
        $renderingAlgoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn(false);
        $executorSpy->expects($this->never())->method("getContents");
        $executorSpy->expects($this->never())->method("dispatch");
        $template = new TemplateEngine(
            $renderingAlgoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->render(self::EXISTING_FILE, new HtmlSafeContext($obj));
    }

    #[TestDox("Shall return content string from given template with object variables bound")]
    public function test3a()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound => "content of template file";
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn($algo);
        $executorSpy->expects($this->once())->method("getContents")->willReturn($algo());
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
    }

    #[TestDox("Shall return content string from given template with optional object is not given")]
    public function test3b()
    {
        $algo = fn (): string|FileNotFound => "";
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->never())->method("bind");
        $executorSpy->expects($this->once())->method("getContents")->willReturn($algo());
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->apply(self::EXISTING_FILE);
    }

    #[TestDox("Shall not execute stream content algorithm when binding fails")]
    public function test4()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound => "";
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn(false);
        $executorSpy->expects($this->never())->method("getContents");
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
    }

    #[TestDox("Shall return value when algorithm returns boolean value")]
    public function test5()
    {
        $obj = new stdClass();
        $algo = fn (): bool|FileNotFound => true;
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn($algo);
        $executorSpy->expects($this->once())->method("dispatch")->willReturn(true);
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $value = $template->render(self::EXISTING_FILE, new HtmlSafeContext($obj));
        $this->assertTrue($value);
    }

    #[TestDox("Shall return string when algorithm returns string value")]
    public function test6()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound => self::FAKE_CONTENTS;
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn($algo);
        $executorSpy->expects($this->once())->method("getContents")->willReturn(self::FAKE_CONTENTS);
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $value = $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
        $this->assertSame(self::FAKE_CONTENTS, $value);
    }

    #[TestDox("Shall allow basename of template file as argument")]
    #[DataProvider("resolvedpaths")]
    public function test7(string $basename, string $expectedResolvedPath)
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound => "";
        $algoFactory = new class($algo) implements TemplatingStrategyInterface {
            public function __construct(private Closure $algo)
            {

            }
            public function getAlgorithm(): Closure
            {
                return $this->algo;
            }
        };
        /**
         * @var MockObject&Binder
         */
        $binderSpy = $this->createMock(Binder::class);
        /**
         * @var MockObject&Dispatcher
         */
        $executorSpy = $this->createMock(Dispatcher::class);
        $binderSpy->expects($this->once())->method("bind")->willReturn($algo);
        $executorSpy->expects($this->once())->method("getContents")->with($algo, $expectedResolvedPath);
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $processDir = getcwd();
        chdir("tests/__templates__");
        $template->apply($basename, new HtmlSafeContext($obj));
        chdir($processDir);
    }

    #[TestDox("Shall return FileNotFound when given file does not exist")]
    #[TestWith(["NON_EXISTING_FILE"])]
    public function test8(string $nonExistingFile)
    {
        $template = new TemplateEngine();
        $result = $template->apply($nonExistingFile);
        $this->assertInstanceOf(FileNotFound::class, $result);
    }
}
