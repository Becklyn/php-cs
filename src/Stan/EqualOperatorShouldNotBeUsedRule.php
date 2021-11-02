<?php declare(strict_types=1);

namespace Becklyn\PhpCs\Stan;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @author Marko Vujnovic <mv@becklyn.com>
 *
 * @since  2021-09-01
 */
class EqualOperatorShouldNotBeUsedRule implements Rule
{
    /**
     * @inheritdoc
     */
    public function getNodeType() : string
    {
        return Equal::class;
    }


    /**
     * @inheritdoc
     */
    public function processNode(Node $node, Scope $scope) : array
    {
        return [
            RuleErrorBuilder::message("The 'equal' operator ('==') should only be used in special cases like comparing data transfer objects for their values. Otherwise, the 'identical' operator ('===') should be used instead.")->build(),
        ];
    }
}
