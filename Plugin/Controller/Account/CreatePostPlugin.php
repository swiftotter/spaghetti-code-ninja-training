<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Plugin\Controller\Account;

use Magento\Cms\Helper\Page;
use Magento\Customer\Controller\Account\CreatePost;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\ResourceModel\Coupon;

class CreatePostPlugin
{
    private ManagerInterface $manager;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private RuleRepositoryInterface $ruleRepository;
    private Coupon $coupon;
    private CouponFactory $couponFactory;
    private Session $session;
    private Page $page;

    /**
     * @param ManagerInterface $manager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RuleRepositoryInterface $ruleRepository
     * @param Coupon $coupon
     * @param CouponFactory $couponFactory
     * @param Session $session
     * @param Page $page
     */
    public function __construct(
        ManagerInterface $manager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RuleRepositoryInterface $ruleRepository,
        Coupon $coupon,
        CouponFactory $couponFactory,
        Session $session,
        Page $page
    ) {
        $this->manager = $manager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->ruleRepository = $ruleRepository;
        $this->coupon = $coupon;
        $this->couponFactory = $couponFactory;
        $this->session = $session;
        $this->page = $page;
    }

    /**
     * @param CreatePost $subject
     * @param Redirect $result
     * @return Redirect
     */
    public function afterExecute(CreatePost $subject, Redirect $result): Redirect
    {
        $last = $this->manager->getMessages()->getLastAddedMessage();
        if ($last && $last->getType() === MessageInterface::TYPE_SUCCESS) {
            $email = $subject->getRequest()->getParam('email');
            if (strpos($email, '@swiftotter.com') !== false) {
                $this->manager->addNoticeMessage(__('Hello fellow Otter!'));
            }
            $firstname = $subject->getRequest()->getParam('firstname');
            if ($firstname === 'Michal') {
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('name', 'Michal Promo')
                    ->setPageSize(1)
                    ->create();
                $rules = $this->ruleRepository->getList($searchCriteria)->getItems();
                $rule = $rules[0];
                $coupon = $this->couponFactory->create();
                $this->coupon->loadPrimaryByRule($coupon, $rule->getRuleId());
                $this->manager->addNoticeMessage(__(
                    'Use coupon code "%1" for special Michal Promo!',
                    $coupon->getCode()
                ));
            }
            if ($this->session->getCustomerId() == 10) {
                $url = $this->page->getPageUrl('10th-customer');
                $result->setUrl($url);
            }
            if ($this->session->getCustomerId() <= 20) {
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('name', 'First Customers Promo')
                    ->setPageSize(1)
                    ->create();
                $rules = $this->ruleRepository->getList($searchCriteria)->getItems();
                $rule = $rules[0];
                $coupon = $this->couponFactory->create();
                $this->coupon->loadPrimaryByRule($coupon, $rule->getRuleId());
                $this->manager->addNoticeMessage(__(
                    'Use coupon code "%1" for special First Customers Promo!',
                    $coupon->getCode()
                ));
            }
        }
        return $result;
    }
}
