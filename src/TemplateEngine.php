<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Closure;

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

    private Closure $renderingAlgo;

    public function __construct(
        TemplatingStrategyInterface $renderingAlgoFactory = new StreamContentStrategy(),
        private Binder $binder = new Binder(),
        private Dispatcher $dispatcher = new Dispatcher(),
    ) {
        $this->renderingAlgo = $renderingAlgoFactory->getAlgorithm();
    }

    /**
     * Returns the content string
     */
    public function apply(string $givenPath, ?HtmlSafeContext $context = null): string|FileNotFound|BindFailed
    {
        $filename = $this->resolveBasename($givenPath);

        if ($filename instanceof FileNotFound) {
            return $filename; // file not found
        }

        if ($context === null) {
            return $this->dispatcher->getContents($this->renderingAlgo, $filename);
        }

        $bound = $this->binder->bind($this->renderingAlgo, $context);

        if ($bound === false) {
            return new BindFailed();
        }

        return $this->dispatcher->getContents($bound, $filename);
    }

    /**
     * Displays the content
     */
    public function render(string $pathToTemplate, HtmlSafeContext $context): bool|FileNotFound|BindFailed
    {
        $bound = $this->binder->bind($this->renderingAlgo, $context);

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
        if (file_exists($givenPath) === true) {
            return $givenPath;
        }
        foreach (self::ALLOWED_EXTENSIONS as $allowedExt) {
            $path = "src/templates/$givenPath.$allowedExt";
            if (file_exists($path) === true) {
                return $path;
            }
        }
        // @codeCoverageIgnore
        return new FileNotFound();
    }
}
