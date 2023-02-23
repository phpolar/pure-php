<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

/**
 * Provides support for pure and secure PHP templates
 */
final class TemplateEngine
{
    private const ALLOWED_EXTENSIONS = [
        "php",
        "phtml",
        "html",
    ];
    public function __construct(
        private TemplatingStrategyInterface $renderingAlgoFactory,
        private Binder $binder,
        private Dispatcher $dispatcher,
    ) {
    }

    /**
     * Returns the content string
     */
    public function apply(string $givenPath, ?HtmlSafeContext $context = null): string|FileNotFound|BindFailed
    {
        $renderingAlgo = $this->renderingAlgoFactory->getAlgorithm();
        $algo = $context === null ? $renderingAlgo : $this->binder->bind($renderingAlgo, $context);
        if ($algo === false) {
            return new BindFailed();
        }
        $result = $this->resolveBasename($givenPath);
        if ($result instanceof FileNotFound) {
            return $result;
        }
        return $this->dispatcher->getContents($algo, $result);
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

    /**
     * Allow for supplying the basename only.
     *
     * An attempt will be made to locate
     * the file in `src/templates` relative
     * to the root of the project.
     *
     * Files with `.php` and `.phtml` extensions
     * are allowed.
     */
    private function resolveBasename(string $givenPath): string|FileNotFound
    {
        if (file_exists($givenPath)) {
            return $givenPath;
        }
        foreach (self::ALLOWED_EXTENSIONS as $allowedExt) {
            $path = "src/templates/$givenPath.$allowedExt";
            if (file_exists($path)) {
                return $path;
            }
        }
        return new FileNotFound();
    }
}
