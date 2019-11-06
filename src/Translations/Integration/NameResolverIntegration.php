<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Integration;

use PhpParser\NodeVisitor\NameResolver;
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;
use Translation\Extractor\Visitor\Visitor;

class NameResolverIntegration extends NameResolver implements Visitor
{
    /**
     * @inheritDoc
     */
    public function init (SourceCollection $collection, SplFileInfo $file) : void
    {
    }
}
