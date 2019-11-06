<?php declare(strict_types=1);

namespace Tests\Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Integration\NameResolverIntegration;
use Becklyn\PhpCs\Translations\Visitor\AbstractVisitor;
use Symfony\Component\Finder\Finder;
use Translation\Extractor\Extractor;
use Translation\Extractor\FileExtractor\PHPFileExtractor;
use Translation\Extractor\Model\SourceLocation;

trait PhpExtractorTestTrait
{
    /**
     * @param AbstractVisitor $abstractVisitor
     * @param string[]        $dirs
     *
     * @return array
     */
    private function extractMessagesWithVisitorFrom (AbstractVisitor $abstractVisitor, array $dirs) : array
    {
        // prefix with fixtures path
        $dirs = \array_map(
            function ($path)
            {
                return __DIR__ . "/../../fixtures/" . \ltrim($path, "/");
            },
            $dirs
        );

        $extractor = new Extractor();

        $fileExtractor = new PHPFileExtractor();

        // add FQCN first
        $fileExtractor->addVisitor(new NameResolverIntegration());

        // register visitor to test
        $fileExtractor->addVisitor($abstractVisitor);

        $extractor->addFileExtractor($fileExtractor);

        $messages = [];

        /** @var SourceLocation $location */
        foreach ($extractor->extract(Finder::create()->name("*.php")->in($dirs)) as $location)
        {
            $domain = $location->getContext()["domain"] ?? "messages";
            $messages[$domain][] = $location->getMessage();
        }

        return $messages;
    }
}
