<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use PhpParser\Node;

class BackendTranslatorVisitor extends AbstractVisitor
{
    /**
     * @inheritDoc
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof Node\Expr\MethodCall)
        {
            return;
        }

        if (!\is_string($node->name) && !$node->name instanceof Node\Identifier)
        {
            return;
        }

        $method = (string) $node->name;
        $caller = $node->var;
        $callerName = \property_exists($caller, "name") ? (string) $caller->name : '';

        if (
            "trans" === $method
            && $callerName === "backendTranslator"
            && ($caller instanceof Node\Expr\Variable || $caller instanceof Node\Expr\MethodCall)
        )
        {
            if (null !== $label = $this->getStringArgument($node, 1))
            {
                $this->addLocation(
                    $label,
                    $node->getAttribute("startLine"),
                    $node,
                    ["domain" => "backend"]
                );
            }
        }
    }
}
