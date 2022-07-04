<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\SpecialAction;

use Magento\Customer\Model\Customer;
use Magento\Framework\Controller\Result\Redirect;
use SwiftOtter\Spaghetti\Action\ExecutePromoSpecialAction;
use SwiftOtter\Spaghetti\Api\SpecialActionInterface;

class MichalPromo implements SpecialActionInterface
{
    private ExecutePromoSpecialAction $executePromoSpecialAction;

    public function __construct(ExecutePromoSpecialAction $executePromoSpecialAction)
    {
        $this->executePromoSpecialAction = $executePromoSpecialAction;
    }

    public function isApplicable(Customer $customer): bool
    {
        return $customer->getData('firstname') === 'Michal';
    }

    public function execute(Redirect $result): void
    {
        $this->executePromoSpecialAction->execute('Michal Promo');
    }
}
