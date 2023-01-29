<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;

/**
 * Triggers the output stream
 */
final class FileRenderer implements RenderingAlgorithmInterface
{
    /**
     * Return the implementation
     */
    public function getAlgorithm(): Closure
    {
        /**
         * @suppress PhanUnreferencedClosure
         */
        return function (string $pathToTemplate) {
            ob_start();
            include $pathToTemplate;
            return ob_end_flush();
        };
    }
}
