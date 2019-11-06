<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use PhpParser\Node;

/**
 * Searches for calls using the backend translator
 */
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

        if (
            $this->isNamedCall($node, "backendTranslator", "trans")
            || $this->isNamedCall($node, "backendTranslator", "t")
            || $this->isNamedCall($node, "translator", "t")
        )
        {
            if (null !== $label = $this->getStringArgument($node, 0))
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


    /**
     * @param Node\Expr\MethodCall $node
     * @param string               $caller
     * @param string               $method
     *
     * @return bool
     */
    private function isNamedCall (Node\Expr\MethodCall $node, string $caller, string $method) : bool
    {
        $callerNode = $node->var;
        $callerName = \property_exists($callerNode, "name") ? (string) $callerNode->name : '';

        return (
            $method === (string) $node->name
            && $callerName === $caller
            && ($callerNode instanceof Node\Expr\Variable || $callerNode instanceof Node\Expr\MethodCall)
        );
    }
}
