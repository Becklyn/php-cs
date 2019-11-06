<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Visitor\FormOptionsVisitor\ChildFormLabelsVisitor;
use Becklyn\PhpCs\Translations\Visitor\Helper\ParserInteractionTrait;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use Symfony\Component\Form\AbstractType;

/**
 *
 */
class FormOptionLabelsVisitor extends AbstractVisitor
{
    use ParserInteractionTrait;

    /**
     * @inheritDoc
     *
     * @return Node[]|void|null Array of nodes
     */
    public function enterNode (Node $node)
    {
        if (!$node instanceof Node\Stmt\Class_)
        {
            return null;
        }

        $className = $this->getClassName($node);

        if ("" === $className)
        {
            return null;
        }

        // only act on abstract types
        if (!\is_a($className, AbstractType::class, true))
        {
            return null;
        }


        $buildFormMethod = $node->getMethod("buildForm");

        if (null === $buildFormMethod)
        {
            return null;
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

        return null;
    }
}
