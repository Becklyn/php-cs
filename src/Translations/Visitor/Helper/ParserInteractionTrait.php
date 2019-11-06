<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor\Helper;

use PhpParser\Node\Stmt\Class_;

trait ParserInteractionTrait
{
    /**
     *
     */
    private function getClassName (Class_ $node) : string
    {
        return \property_exists($node, "namespacedName")
            ? (string) $node->namespacedName
            : (string) $node->name;
    }
}
