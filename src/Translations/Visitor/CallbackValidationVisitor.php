<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Visitor;

use Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Searches for methods that are annotated with @Callback.
 *
 * Then tries to find a parameter that is typed ExecutionContext / ExecutionContextInterface and tries to find calls
 * to a method called "buildViolation" on it.
 */
class CallbackValidationVisitor extends AbstractVisitor
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
     * @var string|null
     */
    private $parameterName;


    /**
     *
     */
    public function __construct ()
    {
        $this->reader = new AnnotationReader();
    }


    /**
     * @inheritDoc
     */
    public function enterNode (Node $node)
    {
        switch (true)
        {
            case $node instanceof ClassLike:
                $this->handleClassLike($node);
                break;

            case $node instanceof ClassMethod:
                $this->handleClassMethod($node);
                break;

            case $node instanceof MethodCall:
                $this->handleMethodCall($node);
                break;
        }
    }


    /**
     * @param ClassLike $node
     */
    private function handleClassLike (ClassLike $node) : void
    {
        $this->classStack[] = \property_exists($node, "namespacedName")
            ? (string) $node->namespacedName
            : (string) $node->name;
    }


    /**
     * @param ClassMethod $node
     */
    private function handleClassMethod (ClassMethod $node) : void
    {
        $method = (string) $node->name;
        $class = \end($this->classStack);
        $this->parameterName = null;

        // anonymous classes are not supported
        if ("" === $class)
        {
            return;
        }

        $annotation = $this->reader->getMethodAnnotation(new \ReflectionMethod($class, $method), Callback::class);

        if (null !== $annotation)
        {
            $this->parameterName = $this->findNameOfParameter($node->params);
        }
    }


    /**
     * @param MethodCall $node
     */
    private function handleMethodCall (MethodCall $node) : void
    {
        $method = (string) $node->name;
        $caller = $node->var;
        $callerName = \property_exists($caller, "name") ? (string) $caller->name : '';

        if (null === $this->parameterName)
        {
            return;
        }

        if (
            "buildViolation" === $method
            && $callerName === $this->parameterName
            && ($caller instanceof Node\Expr\Variable)
        )
        {
            $message = $this->getStringArgument($node, 0);

            if (null !== $message)
            {
                $this->addLocation(
                    $message,
                    $node->getAttribute("startLine"),
                    $node,
                    ["domain" => "validators"]
                );
            }
        }
    }


    /**
     * @param Node\Param[] $params
     *
     * @return string|null
     */
    private function findNameOfParameter (array $params) : ?string
    {
        foreach ($params as $param)
        {
            if (
                \in_array((string) $param->type, [
                    ExecutionContextInterface::class,
                    ExecutionContext::class,
                ], true)
            )
            {
                return (string) $param->var->name;
            }
        }

        return null;
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

        if ($node instanceof ClassMethod)
        {
            $this->parameterName = null;
        }
    }
}
