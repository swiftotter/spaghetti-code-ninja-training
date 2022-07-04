<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Api;

use Magento\Customer\Model\Customer;
use Magento\Framework\Controller\Result\Redirect;

/**
 * @api
 */
interface SpecialActionInterface
{
    public function isApplicable(Customer $customer): bool;

    public function execute(Redirect $result): void;
}
