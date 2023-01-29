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
    /**
     * @suppress PhanTypeMismatchArgumentInternal
     */
    public function __construct(object $obj)
    {
        array_walk(
            $obj,
            fn ($v, string $name) => $this->$name = $this->convertVal($v)
        );
    }

    private function convertVal(mixed $val): mixed
    {
        return match (gettype($val)) {
            "string" => new HtmlSafeString($val),
            "array" => array_map(self::convertVal(...), $val),
            "resource", "resource (closed)" => "",
            "object" => match ($val instanceof Stringable) {
                true => new HtmlSafeString((string) $val),
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
        array_walk_recursive($copy, fn (&$v) => $v = $this->convertVal($v));
        return $copy;
    }

    /**
     * @codeCoverageIgnore
     * @suppress PhanUnusedPublicFinalMethodParameter
     */
    public function __call(mixed $a, mixed $b): never
    {
        throw new RuntimeException("bang");
    }

    /**
     * @codeCoverageIgnore
     * @suppress PhanUnusedPublicFinalMethodParameter
     */
    public function __get(string $a): never
    {
        throw new RuntimeException("bang");
    }

    /**
     * @codeCoverageIgnore
     */
    public function __invoke(): never
    {
        throw new RuntimeException("bang");
    }

    /**
     * @codeCoverageIgnore
     */
    public function __serialize(): never
    {
        throw new RuntimeException("bang");
    }
}
