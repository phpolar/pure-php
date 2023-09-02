<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use RuntimeException;
use Stringable;

/**
 * Converts the string and Stringable properties and nested properties of an object
 * to HtmlSafeString
 */
final class HtmlSafeContext
{
    private object $client;

    public function __construct(object $obj)
    {
        $this->client = clone $obj;
        foreach (get_object_vars($obj) as $propName => $value) {
            $this->client->$propName = $this->convertVal($value instanceof Stringable ? (string) $value : $value);
        }
        // @codeCoverageIgnore
    }

    /**
     * Allow the binder to retrieve the inner object.
     */
    public function receive(Binder $binder): object
    {
        return $binder->getClient($this->client);
    }

    private function convertVal(mixed $val): mixed
    {
        return match (gettype($val)) {
            "string" => (string) new HtmlSafeString($val),
            "array" => array_map(self::convertVal(...), $val),
            "resource", "resource (closed)" => "",
            "object" => $this->convertProps($val),
            default => $val,
        };
    }

    /**
     * @suppress PhanTypePossiblyInvalidCloneNotObject
     * @suppress PhanPartialTypeMismatchReturn
     * @suppress PhanTypeMismatchArgumentInternal
     */
    private function convertProps(object &$obj): object
    {
        $copy = clone $obj;
        array_walk_recursive($copy, fn (&$val) => $val = $this->convertVal($val instanceof Stringable ? (string) $val : $val));
        return $copy;
    }

    public function __invoke(): never
    {
        throw new RuntimeException("Non-invokable class.");
    }

    public function __serialize(): never
    {
        throw new RuntimeException("Non-serializable class.");
    }
}
