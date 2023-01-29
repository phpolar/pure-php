<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

/**
 * Provides support for pure and secure PHP templates
 */
final class TemplateEngine
{
    public function __construct(
        private RenderingAlgorithmInterface $renderingAlgoFactory,
        private Binder $binder,
        private Dispatcher $dispatcher,
    ) {
    }

    /**
     * Displays the content
     */
    public function render(string $pathToTemplate, HtmlSafeContext $context): void
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $bound = $this->binder->bind($renderingAlgo, $context);
        if ($bound !== null) {
            $this->dispatcher->execute($bound, $pathToTemplate);
        }
    }
}
