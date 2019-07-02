<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Visitor\FormOptionsVisitor\ChildFormLabelsVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use Symfony\Component\Form\AbstractType;

/**
 *
 */
class FormOptionLabelsVisitor extends AbstractVisitor
{
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

        // only act on abstract types
        if (!\is_a($className, AbstractType::class, true))
        {
            return;
        }


        $buildFormMethod = $node->getMethod("buildForm");

        if (null === $buildFormMethod)
        {
            return;
        }

        // traverse through form and fetch all options
        $traverser = new NodeTraverser();
        $formOptionsVisitor = new ChildFormLabelsVisitor();
        $traverser->addVisitor($formOptionsVisitor);
        $traverser->traverse([$buildFormMethod]);

        foreach ($formOptionsVisitor->getMessages() as $message)
        {
            $this->addLocation(
                $message,
                $node->getAttribute("startLine"),
                $node,
                ["domain" => "form"]
            );
        }
    }



    /**
     * @param Node\Stmt\Class_ $node
     *
     * @return string
     */
    private function getClassName (Node\Stmt\Class_ $node) : string
    {
        return \property_exists($node, "namespacedName")
            ? (string) $node->namespacedName
            : (string) $node->name;
    }
}
