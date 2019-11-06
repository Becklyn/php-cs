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
     *
     * @return Node[]|void|null Array of nodes
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof Node\Expr\MethodCall)
        {
            return null;
        }

        if (!$node->name instanceof Node\Identifier)
        {
            return null;
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
     *
     */
    private function isNamedCall (Node\Expr\MethodCall $node, string $caller, string $method) : bool
    {
        $callerNode = $node->var;
        $callerName = \property_exists($callerNode, "name") ? (string) $callerNode->name : '';

        return
            $method === (string) $node->name
            && $callerName === $caller
            && ($callerNode instanceof Node\Expr\Variable || $callerNode instanceof Node\Expr\PropertyFetch);
    }
}
