<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Visitor\Helper\ParserInteractionTrait;
use PhpParser\Node;
use Symfony\Component\Validator\Constraint;

/**
 * Extracts defaults from custom validation constraints
 */
class CustomConstraintDefaultMessagesVisitor extends AbstractVisitor
{
    use ParserInteractionTrait;

    /**
     * @inheritDoc
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof Node\Stmt\Class_)
        {
            return;
        }

        $className = $this->getClassName($node);

        if ("" === $className)
        {
            return;
        }

        // only act on constraints
        if (!\is_a($className, Constraint::class, true))
        {
            return;
        }

        $class = new \ReflectionClass($className);

        foreach ($class->getDefaultProperties() as $key => $value)
        {
            if (\is_string($value) && ("message" === $key || \preg_match('~^.+Message$~', $key)))
            {
                $this->addLocation(
                    $value,
                    $node->getAttribute("startLine"),
                    $node,
                    ["domain" => "validators"]
                );
            }
        }
    }
}
