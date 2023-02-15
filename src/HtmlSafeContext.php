<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use RuntimeException;
use Stringable;

/**
 * Converts the string and Stringable properties and nested properties of an object
 * to HtmlSafeString
 */
final class HtmlSafeContext
{
    private object $innerObject19;

    public function __construct(object $obj)
    {

        $this->innerObject19 = clone $obj;
        foreach (get_object_vars($obj) as $propName => $value) {
             $this->$propName = $this->convertVal($value);
        }
    }

    private function convertVal(mixed $val): mixed
    {
        return match (gettype($val)) {
            "string" => (string) new HtmlSafeString($val),
            "array" => array_map(self::convertVal(...), $val),
            "resource", "resource (closed)" => "",
            "object" => match ($val instanceof Stringable) {
                true => (string) new HtmlSafeString((string) $val),
                false => $this->convertProps($val),
            },
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
        array_walk_recursive($copy, fn (&$val) => $val = $this->convertVal($val));
        return $copy;
    }

    /**
     * @suppress PhanUnusedPublicFinalMethodParameter
     */
    public function __call(mixed $methodName, mixed $args): mixed
    {
        if (
            is_string($methodName) === true &&
            method_exists($this->innerObject19, $methodName) === false
        ) {
            return false;
        }
        return $this->innerObject19->$methodName(...$args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __invoke(): never
    {
        throw new RuntimeException("Non-invokable class.");
    }

    /**
     * @codeCoverageIgnore
     */
    public function __serialize(): never
    {
        throw new RuntimeException("Non-serializable class.");
    }
}
