<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use Closure;

/**
 * Binds an object (context) to an output scope
 */
class Binder
{
    /**
     * Streams content of template
     */
    public function bind(Closure $algo, HtmlSafeContext $context): ?Closure
    {
        return Closure::bind($algo, $context);
    }
}
