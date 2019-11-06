<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Translation\Extractor\Visitor\Php\BasePHPVisitor;

abstract class AbstractVisitor extends BasePHPVisitor implements NodeVisitor
{
    /**
     * @inheritDoc
     *
     * @return Node[]|void|null Array of nodes
     */
    public function beforeTraverse (array $nodes)
    {
    }

    /**
     * @inheritDoc
     *
     * @return int|Node|Node[]|void|null Replacement node (or special return value)
     */
    public function leaveNode (Node $node)
    {
    }


    /**
     * @inheritDoc
     *
     * @return Node[]|void|null Array of nodes
     */
    public function afterTraverse (array $nodes)
    {
    }
}
