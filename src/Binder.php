<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Closure;

/**
 * Binds an object (context) to an output scope
 */
class Binder
{
    /**
     * Streams content of template
     */
    public function bind(Closure $algo, HtmlSafeContext $context): Closure|false
    {
        return Closure::bind($algo, $context) ?? false;
    }
}
