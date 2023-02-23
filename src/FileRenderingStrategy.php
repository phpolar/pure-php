<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Closure;

/**
 * Triggers the output stream
 */
final class FileRenderingStrategy implements TemplatingStrategyInterface
{
    /**
     * Return the implementation
     */
    public function getAlgorithm(): Closure
    {
        /**
         * @suppress PhanUnreferencedClosure
         */
        return function (string $pathToTemplate): bool|FileNotFound {
            if (file_exists($pathToTemplate) === false) {
                return new FileNotFound();
            }
            ob_start();
            include $pathToTemplate;
            return ob_end_flush();
        };
    }
}
