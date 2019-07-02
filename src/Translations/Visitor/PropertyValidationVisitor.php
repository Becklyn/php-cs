<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Becklyn\PhpCs\Translations\Constraint\ConstraintMessageExtractor;
use Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;

/**
 * Searches for constraints that are placed on a property.
 */
class PropertyValidationVisitor extends AbstractVisitor
{
    /**
     * @var string[]
     */
    private $classStack = [];


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
     */
    public function enterNode (Node $node)
    {
        if ($node instanceof ClassLike)
        {
            if (!\property_exists($node, "namespacedName") || (!\is_string($node->namespacedName) && !$node->namespacedName instanceof Node\Name))
            {
                $this->classStack[] = "?";
                return;
            }

            $this->classStack[] = (string) $node->namespacedName;
            return;
        }

        if (!$node instanceof Property)
        {
            return;
        }

        if (count($node->props) !== 1)
        {
            throw new \Exception("Can't parse prop with multiple propertyproperties.");
        }

        $name = (string) $node->props[0]->name;
        $class = \end($this->classStack);
        $annotations = $this->reader->getPropertyAnnotations(new \ReflectionProperty($class, $name));

        foreach ($this->constraintMessageExtractor->extractMessages($annotations) as $message)
        {
            $this->addLocation(
                $message,
                $node->getAttribute("startLine"),
                $node,
                ["domain" => "validators"]
            );
        }
    }


    /**
     * @inheritDoc
     */
    public function leaveNode (Node $node)
    {
        if ($node instanceof ClassLike)
        {
            \array_pop($this->classStack);
        }
    }
}
