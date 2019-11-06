<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Constraint\ConstraintMessageExtractor;
use Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Node;

/**
 * Searches for constraints that are placed on a class.
 */
class ClassValidationVisitor extends AbstractVisitor
{
    /**
     * @var AnnotationReader
     */
    private $reader;


    /**
     * @var ConstraintMessageExtractor
     */
    private $constraintMessageExtractor;


    /**
     *
     */
    public function __construct ()
    {
        $this->reader = new AnnotationReader();
        $this->constraintMessageExtractor = new ConstraintMessageExtractor();
    }


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

        if (!\property_exists($node, "namespacedName") || (!$node->namespacedName instanceof Node\Name))
        {
            return null;
        }

        $className = (string) $node->namespacedName;
        $annotations = $this->reader->getClassAnnotations(new \ReflectionClass($className));

        foreach ($this->constraintMessageExtractor->extractMessages($annotations) as $message)
        {
            $this->addLocation(
                $message,
                $node->getAttribute("startLine"),
                $node,
                ["domain" => "validators"]
            );
        }

        return null;
    }
}
