<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Action;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\ResourceModel\Coupon as CouponResource;
use SwiftOtter\Spaghetti\Api\GetCouponCodeByRuleNameInterface;

use function reset;

class GetCouponCodeByRuleName implements GetCouponCodeByRuleNameInterface
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private RuleRepositoryInterface $ruleRepository;
    private CouponResource $couponResource;
    private CouponFactory $couponFactory;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RuleRepositoryInterface $ruleRepository,
        CouponResource $couponResource,
        CouponFactory $couponFactory
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->ruleRepository = $ruleRepository;
        $this->couponResource = $couponResource;
        $this->couponFactory = $couponFactory;
    }

    public function execute(string $ruleName): ?string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('name', $ruleName)
            ->setPageSize(1)
            ->create();
        $rules = $this->ruleRepository->getList($searchCriteria)->getItems();
        if (empty($rules)) {
            return null;
        }
        $rule = reset($rules);
        $coupon = $this->couponFactory->create();
        $this->couponResource->loadPrimaryByRule($coupon, $rule->getRuleId());
        if (!$coupon->getId()) {
            return null;
        }
        return $coupon->getCode();
    }
}
