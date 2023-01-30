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
    public function apply(string $pathToTemplate, HtmlSafeContext $context): string|FileNotFound|BindFailed
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $bound = $this->binder->bind($renderingAlgo, $context);
        if ($bound === null) {
            return new BindFailed();
        }
        $result = $this->dispatcher->execute($bound, $pathToTemplate);
        return is_bool($result) === true ? "" : $result;
    }

    /**
     * Displays the content
     */
    public function render(string $pathToTemplate, HtmlSafeContext $context): bool|FileNotFound|BindFailed
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $bound = $this->binder->bind($renderingAlgo, $context);
        if ($bound === null) {
            return new BindFailed();
        }
        $result = $this->dispatcher->execute($bound, $pathToTemplate);
        return is_string($result) === true ? false : $result;
    }
}
