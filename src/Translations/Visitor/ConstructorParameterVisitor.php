<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;

/**
 * Extracts parameters
 */
class ConstructorParameterVisitor extends AbstractVisitor
{
    /**
     * All classes with which parameter is a translation message
     */
    private const LOCATIONS = [
        "Becklyn\\RadBundle\\Exception\\LabeledEntityRemovalBlockedException" => [
            "arg" => 2,
            "domain" => "backend",
        ],
    ];


    /**
     * @inheritDoc
     *
     * @return Node[]|void|null Array of nodes
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof New_)
        {
            return null;
        }

        $className = $node->class instanceof Node\Name
            ? $node->class->toString()
            : (string) $node->class->name;

        foreach (self::LOCATIONS as $expectedClass => $config)
        {
            if ($expectedClass !== $className)
            {
                continue;
            }

            $message = $this->parseArgAsString($node->args, $config["arg"]);

            if (null !== $message)
            {
                $this->addLocation(
                    $message,
                    $node->getAttribute("startLine"),
                    $node,
                    ["domain" => $config["domain"]]
                );
            }
        }

        return null;
    }


    /**
     * @param Arg[] $args
     */
    private function parseArgAsString (array $args, int $index) : ?string
    {
        $arg = $args[$index] ?? null;

        if (null === $arg)
        {
            return null;
        }

        $value = $arg->value;

        return $value instanceof String_
            ? $value->value
            : null;
    }
}
