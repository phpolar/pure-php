<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

/**
 * Provides support for pure and secure PHP templates
 */
final class TemplateEngine
{
    public function __construct(
        private TemplatingStrategyInterface $renderingAlgoFactory,
        private Binder $binder,
        private Dispatcher $dispatcher,
    ) {
    }

    /**
     * Returns the content string
     */
    public function apply(string $pathToTemplate, ?HtmlSafeContext $context = null): string|FileNotFound|BindFailed
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $algo = $context === null ? $renderingAlgo : $this->binder->bind($renderingAlgo, $context);
        if ($algo === false) {
            return new BindFailed();
        }
        return $this->dispatcher->getContents($algo, $pathToTemplate);
    }

    /**
     * Displays the content
     */
    public function render(string $pathToTemplate, HtmlSafeContext $context): bool|FileNotFound|BindFailed
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $bound = $this->binder->bind($renderingAlgo, $context);
        if ($bound === false) {
            return new BindFailed();
        }
        return $this->dispatcher->dispatch($bound, $pathToTemplate);
    }
}
