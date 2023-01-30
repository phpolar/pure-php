<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;

/**
 * Triggers the output stream
 */
final class StreamContentStrategy implements TemplatingStrategyInterface
{
    /**
     * Return the implementation
     */
    public function getAlgorithm(): Closure
    {
        /**
         * @suppress PhanUnreferencedClosure
         */
        return function (string $pathToTemplate): string|FileNotFound {
            if (file_exists($pathToTemplate) === false) {
                return new FileNotFound();
            }
            ob_start();
            include $pathToTemplate;
            return (string) ob_get_clean();
        };
    }
}
