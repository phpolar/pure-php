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
        $executorSpy->expects($this->once())->method("execute")->willReturn($algo());
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
        $binderSpy->expects($this->once())->method("bind")->willReturn(null);
        $executorSpy->expects($this->never())->method("execute");
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
    public function test3()
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
        $executorSpy->expects($this->once())->method("execute")->willReturn($algo());
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
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
        $binderSpy->expects($this->once())->method("bind")->willReturn(null);
        $executorSpy->expects($this->never())->method("execute");
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
    }

    /**
     * @testdox Shall return empty string when algorithm returns boolean value
     */
    public function test5()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound|bool => true;
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
        $executorSpy->expects($this->once())->method("execute")->willReturn(true);
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $value = $template->apply(self::EXISTING_FILE, new HtmlSafeContext($obj));
        $this->assertSame("", $value);
    }

    /**
     * @testdox Shall return false when algorithm returns string value
     */
    public function test6()
    {
        $obj = new stdClass();
        $algo = fn (): string|FileNotFound|bool => "some string";
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
        $executorSpy->expects($this->once())->method("execute")->willReturn("some string");
        $template = new TemplateEngine(
            $algoFactory,
            $binderSpy,
            $executorSpy,
        );
        $value = $template->render(self::EXISTING_FILE, new HtmlSafeContext($obj));
        $this->assertFalse($value);
    }
}