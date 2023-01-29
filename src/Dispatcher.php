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
    public function execute(Closure $algo, string ...$arg): void
    {
        $algo(...$arg);
    }
}
