<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Extracts messages from Constraint annotations in Symfony.
 */
class ConstraintMessageExtractor
{
    /**
     * Adds all messages from all constraint annotations
     *
     * @param object[] $annotations
     *
     * @return string[]
     */
    public function extractMessages (array $annotations) : array
    {
        $result = [];

        foreach ($annotations as $constraint)
        {
            if (!$constraint instanceof Constraint)
            {
                continue;
            }

            foreach ($this->extractMessageKeysFromConstraint($constraint) as $message)
            {
                $result[$message] = true;
            }
        }

        return \array_keys($result);
    }


    /**
     *
     */
    private function extractMessageKeysFromConstraint (Constraint $constraint) : array
    {
        $messages = [];
        $reflectionClass = new \ReflectionClass($constraint);

        // fetch all properties that either are "message" or end in "*Message"
        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $property)
        {
            if ("message" === $property->getName() || \preg_match('~^.+Message$~', $property->getName()))
            {
                $messages = $this->extract($messages, $constraint, $property->getName());
            }
        }

        return $messages;
    }


    /**
     * Extracts the message in the property from the constraint and adds it to the list,
     * if it is applicable and not empty.
     */
    private function extract (array $list, Constraint $constraint, string $property) : array
    {
        if (\property_exists($constraint, $property) && $this->shouldAdd($constraint, $property))
        {
            $list[] = $constraint->{$property};
        }

        return $list;
    }


    /**
     * Checks whether the property was actually changed in the constraint
     */
    private function shouldAdd (Constraint $constraint, string $property) : bool
    {
        $value = $constraint->{$property};

        if (!\is_string($value))
        {
            return false;
        }

        $reflectionClass = new \ReflectionClass($constraint);
        $defaultProperties = $reflectionClass->getDefaultProperties();

        // only add if not default value
        return isset($defaultProperties[$property])
            ? $defaultProperties[$property] !== $value
            : true;
    }
}
