<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Phpolar\PhpTemplating\StreamContentStrategy
 * @uses \Phpolar\PhpTemplating\Binder
 * @uses \Phpolar\PhpTemplating\HtmlSafeContext
 * @uses \Phpolar\PhpTemplating\HtmlSafeString
 * @uses \Phpolar\PhpTemplating\FileNotFound
 */
final class StreamContentStrategyTest extends TestCase
{
    const FAKE_TITLE = "FAKE TITLE";
    const PATH_TO_TEMPLATE = "tests/__templates__/a.php";

    /**
     * @testdox Shall replace the template variables with the properties of the bound object
     */
    public function test1()
    {
        $sut = new StreamContentStrategy();
        $algo = $sut->getAlgorithm();
        $binder = new Binder();
        $bound = $binder->bind($algo, new HtmlSafeContext((object) ["title" => self::FAKE_TITLE]));
        $responseContent = $bound(self::PATH_TO_TEMPLATE);
        $this->assertSame(
            sprintf(
                "<h1>%s</h1>",
                self::FAKE_TITLE,
            ),
            $responseContent,
        );
    }

    /**
     * @testdox Shall return instance of FileNotFound when the template file does not exist
     */
    public function test2()
    {
        $sut = new StreamContentStrategy();
        $algo = $sut->getAlgorithm();
        $binder = new Binder();
        $bound = $binder->bind($algo, new HtmlSafeContext((object) ["title" => self::FAKE_TITLE]));
        $responseContent = $bound("a_template_file_that_does_not_exist");
        $this->assertInstanceOf(
            FileNotFound::class,
            $responseContent,
        );
    }
}
