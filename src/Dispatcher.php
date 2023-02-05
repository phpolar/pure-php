<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;

/**
 * Executes an algorithm
 */
class Dispatcher
{
    /**
     * Execute the algorithm
     */
    public function dispatch(Closure $algo, string ...$arg): bool|FileNotFound
    {
        return $algo(...$arg);
    }

    /**
     * Execute the algorithm to return the contents
     */
    public function getContents(Closure $algo, string ...$arg): string|FileNotFound
    {
        return $algo(...$arg);
    }
}
