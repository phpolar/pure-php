<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;

/**
 * Allows for swapping rendering implementations
 */
interface RenderingAlgorithmInterface
{
    /**
     * Returns the rendering implementation
     */
    public function getAlgorithm(): Closure;
}
