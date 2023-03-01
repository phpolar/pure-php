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
        $client = $this->visit($context);
        return $algo->bind($algo, $client) ?? false;
    }

    /**
     * Retrieve the encapsulated client from the context.
     */
    protected function visit(HtmlSafeContext $context): object
    {
        return $context->receive($this);
    }

    /**
     * Retrieve the client of the context.
     */
    public function getClient(object $client): object
    {
        return $client;
    }
}
