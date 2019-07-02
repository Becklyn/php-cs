<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

abstract class AbstractVisitor extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @inheritDoc
     */
    public function beforeTraverse (array $nodes)
    {
    }

    /**
     * @inheritDoc
     */
    public function leaveNode (Node $node)
    {
    }


    /**
     * @inheritDoc
     */
    public function afterTraverse (array $nodes)
    {
    }
}
