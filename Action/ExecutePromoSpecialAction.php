<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Action;

use Magento\Framework\Message\ManagerInterface;
use SwiftOtter\Spaghetti\Api\GetCouponCodeByRuleNameInterface;

class ExecutePromoSpecialAction
{
    private ManagerInterface $messageManager;
    private GetCouponCodeByRuleNameInterface $getCouponCodeByRuleName;

    public function __construct(
        ManagerInterface $messageManager,
        GetCouponCodeByRuleNameInterface $getCouponCodeByRuleName
    ) {
        $this->messageManager = $messageManager;
        $this->getCouponCodeByRuleName = $getCouponCodeByRuleName;
    }

    public function execute(string $ruleName): void
    {
        $couponCode = $this->getCouponCodeByRuleName->execute($ruleName);
        if (!$couponCode) {
            return;
        }
        $this->messageManager->addNoticeMessage(__(
            'Use coupon code "%1" for special %2!',
            $couponCode,
            $ruleName
        ));
    }
}
