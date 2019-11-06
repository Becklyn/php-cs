<?php declare(strict_types=1);

namespace Tests\Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Visitor\BackendTranslatorVisitor;
use PHPUnit\Framework\TestCase;

class BackendTranslatorVisitorTest extends TestCase
{
    use PhpExtractorTestTrait;


    public function testExtract ()
    {
        $extracted = $this->extractMessagesWithVisitorFrom(new BackendTranslatorVisitor(), ["BackendTranslatorVisitor"]);

        self::assertEquals([
            "backend" => [
                "backendTranslator.trans.property",
                "backendTranslator.trans.var",
                "backendTranslator.t.property",
                "backendTranslator.t.var",
                "translator.t.property",
                "translator.t.var",
            ],
        ], $extracted);
    }
}
