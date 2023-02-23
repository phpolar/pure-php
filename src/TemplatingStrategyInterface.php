<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Closure;

/**
 * Allows for swapping rendering implementations
 */
interface TemplatingStrategyInterface
{
    /**
     * Returns the rendering implementation
     */
    public function getAlgorithm(): Closure;
}
