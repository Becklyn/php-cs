<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor\FormOptionsVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeVisitor;

/**
 * Visitor specifically for a single form class.
 */
class ChildFormLabelsVisitor implements NodeVisitor
{
    private const TRANSLATABLE_KEYS = [
        "help" => true,
        "label" => true,
        "placeholder" => true,
        "title" => true,
    ];

    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @inheritDoc
     */
    public function beforeTraverse (array $nodes)
    {
    }


    /**
     * @inheritDoc
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof MethodCall)
        {
            return;
        }

        $methodName = (string) $node->name;
        $variableName = $this->findVariableName($node->var);

        if ("add" !== $methodName || !\in_array($variableName, ["form", "builder"], true))
        {
            return;
        }

        $options = $node->args[2] ?? null;

        if (null !== $options && $options->value instanceof Array_)
        {
            $this->collectMessages($options->value);
        }
    }


    /**
     * Fetches all messages out from the given array
     *
     * @param Array_ $options
     */
    private function collectMessages (Array_ $options) : void
    {
        foreach ($options->items as $arrayItem)
        {
            if (!$arrayItem->key instanceof String_)
            {
                continue;
            }

            $key = (string) $arrayItem->key->value;
            $value = $arrayItem->value;

            if ("attr" === $key && $value instanceof Array_)
            {
                $this->collectMessages($value);
                continue;
            }

            if (!$value instanceof String_)
            {
                continue;
            }

            if (\array_key_exists($key, self::TRANSLATABLE_KEYS))
            {
                $message = (string) $value->value;
                $this->messages[$message] = true;
            }
        }
    }


    /**
     * @param Expr $expression
     *
     * @return string|null
     */
    private function findVariableName (Expr $expression) : ?string
    {
        if ($expression instanceof Variable)
        {
            return (string) $expression->name;
        }

        if ($expression instanceof MethodCall)
        {
            return $this->findVariableName($expression->var);
        }

        return null;
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


    /**
     * @return string[]
     */
    public function getMessages () : array
    {
        return \array_keys($this->messages);
    }
}
