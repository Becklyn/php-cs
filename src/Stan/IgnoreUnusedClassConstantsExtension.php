<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Stan;

use PHPStan\Reflection\ConstantReflection;
use PHPStan\Rules\Constants\AlwaysUsedClassConstantsExtension;

class IgnoreUnusedClassConstantsExtension implements AlwaysUsedClassConstantsExtension
{
    /**
     * @inheritdoc
     */
    public function isAlwaysUsed (ConstantReflection $constant) : bool
    {
        // Most of the unused class constants are used as an enum-replacement (until they're actually replaced with a real enum in PHP 8.1),
        // so we want to keep these configuration-like constants around, since it doesn't really hurt the quality of the code base to keep them.
        return true;
    }
}
