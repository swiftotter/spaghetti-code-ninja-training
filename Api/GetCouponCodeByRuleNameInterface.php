<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Api;

/**
 * @api
 */
interface GetCouponCodeByRuleNameInterface
{
    public function execute(string $ruleName): ?string;
}
