<?php

declare(strict_types=1);

namespace Phpolar\PurePhp;

use Stringable;

/**
 * Represents a string safe for adding to HTML
 */
final class HtmlSafeString implements Stringable
{
    private const EMPTY_STR = "";

    private const JS_DIRECTIVE = "javascript:";

    private const FILTER_STRS = [
        self::JS_DIRECTIVE,
    ];

    public function __construct(private string $strVal)
    {

    }

    /**
     * Convert the object to a string
     */
    public function __toString(): string
    {
        return htmlentities(
                array_reduce(
                    self::FILTER_STRS,
                    self::reduceFilterString(...),
                    $this->strVal,
                ),
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
                "UTF-8",
            );
    }

    private static function reduceFilterString(string $prev, string $str): string
    {
        return $prev === self::EMPTY_STR ? self::EMPTY_STR : (string) str_ireplace($str, self::EMPTY_STR, $prev);
    }
}
