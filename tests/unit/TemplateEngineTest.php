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
    /**
     * @testdox Shall display content from given template with object variables bound
     */
    public function test1()
    {
        $obj = new stdClass();
        $renderingAlgoFactory = new class() implements RenderingAlgorithmInterface {
            public function getAlgorithm(): Closure
            {
                return function () { /** NOOP */};
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
        $binderSpy->expects($this->once())->method("bind")->willReturn(fn() => null);
        $executorSpy->expects($this->once())->method("execute");
        $template = new TemplateEngine(
            $renderingAlgoFactory,
            $binderSpy,
            $executorSpy,
        );
        $template->render("NOOP", new HtmlSafeContext($obj));
    }

    /**
     * @testdox Shall not execute rendering algorithm when binding fails
     */
    public function test2()
    {
        $obj = new stdClass();
        $renderingAlgoFactory = new class() implements RenderingAlgorithmInterface {
            public function getAlgorithm(): Closure
            {
                return function () { /** NOOP */};
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
        $template->render("NOOP", new HtmlSafeContext($obj));
    }
}