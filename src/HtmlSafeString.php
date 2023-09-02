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

    private const UTF_ENCODING = "UTF-8";

    public function __construct(private string $strVal)
    {

    }

    /**
     * Convert the object to a string
     */
    public function __toString(): string
    {
        return htmlentities(
            (string) str_ireplace(self::JS_DIRECTIVE, self::EMPTY_STR, $this->strVal),
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
            self::UTF_ENCODING,
        );
    }
}
