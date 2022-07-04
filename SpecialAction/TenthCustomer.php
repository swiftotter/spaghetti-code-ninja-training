<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\SpecialAction;

use Magento\Cms\Helper\Page as PageHelper;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Customer\Model\Customer;
use Magento\Framework\Controller\Result\Redirect;
use SwiftOtter\Spaghetti\Api\SpecialActionInterface;

class TenthCustomer implements SpecialActionInterface
{
    private PageHelper $pageHelper;
    private PageResource $pageResource;

    public function __construct(PageHelper $pageHelper, PageResource $pageResource)
    {
        $this->pageHelper = $pageHelper;
        $this->pageResource = $pageResource;
    }

    public function isApplicable(Customer $customer): bool
    {
        return (int)$customer->getId() === 10;
    }

    public function execute(Redirect $result): void
    {
        if (!$this->pageResource->checkIdentifier('10th-customer', 0)) {
            return;
        }
        $url = $this->pageHelper->getPageUrl('10th-customer');
        $result->setUrl($url);
    }
}
