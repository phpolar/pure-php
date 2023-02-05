<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;
use stdClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpolar\PhpTemplating\TemplateEngine
 * @uses \Phpolar\PhpTemplating\HtmlSafeContext
 * @uses \Phpolar\PhpTemplating\HtmlSafeString
 */
final class TemplateEngineTest extends TestCase
{
    const EXISTING_FILE = "tests/__templates__/a.php";
    const FAKE_CONTENTS = "some string";

    /**
     * @testdox Shall display content from given template with object variables bound
     */
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

    /**
     * @testdox Shall not execute rendering algorithm when binding fails
     */
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

    /**
     * @testdox Shall return content string from given template with object variables bound
     */
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

    /**
     * @testdox Shall return content string from given template with optional object is not given
     */
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

    /**
     * @testdox Shall not execute stream content algorithm when binding fails
     */
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

    /**
     * @testdox Shall return value when algorithm returns boolean value
     */
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

    /**
     * @testdox Shall return string when algorithm returns string value
     */
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
}