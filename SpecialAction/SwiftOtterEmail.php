<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\SpecialAction;

use Magento\Customer\Model\Customer;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use SwiftOtter\Spaghetti\Api\SpecialActionInterface;

class SwiftOtterEmail implements SpecialActionInterface
{
    private ManagerInterface $messageManager;

    public function __construct(ManagerInterface $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function isApplicable(Customer $customer): bool
    {
        return strpos($customer->getEmail(), '@swiftotter.com') !== false;
    }

    public function execute(Redirect $result): void
    {
        $this->messageManager->addNoticeMessage(__('Hello fellow Otter!'));
    }
}
