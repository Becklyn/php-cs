<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Translations\Constraint;

use Symfony\Component\Validator\Constraint;

class ConstraintMessageExtractor
{
    /**
     * Adds all messages from all constraint annotations
     *
     * @param object[] $annotations
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
     * @param Constraint $constraint
     *
     * @return array
     */
    private function extractMessageKeysFromConstraint (Constraint $constraint) : array
    {
        $messages = [];

        if (\property_exists($constraint, "message"))
        {
            $messages[] = $constraint->message;
        }

        return $messages;
    }
}
