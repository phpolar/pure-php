<?php

declare(strict_types=1);

namespace Phpolar\PhpTemplating;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StreamContentStrategy::class)]
#[UsesClass(Binder::class)]
#[UsesClass(HtmlSafeContext::class)]
#[UsesClass(HtmlSafeString::class)]
#[UsesClass(FileNotFound::class)]
final class StreamContentStrategyTest extends TestCase
{
    const FAKE_TITLE = "FAKE TITLE";
    const PATH_TO_TEMPLATE = "tests/__templates__/a.php";

    #[TestDox("Shall replace the template variables with the properties of the bound object")]
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

    #[TestDox("Shall return instance of FileNotFound when the template file does not exist")]
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
