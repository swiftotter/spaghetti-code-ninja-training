<?php

declare(strict_types=1);

namespace SwiftOtter\Spaghetti\Plugin;

use InvalidArgumentException;
use Magento\Customer\Controller\Account\CreatePost;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use SwiftOtter\Spaghetti\Api\SpecialActionInterface;

class TriggerSpecialActionsAfterCustomerRegistration
{
    private ManagerInterface $messageManager;
    private Session $session;
    /** @var SpecialActionInterface[] */
    private array $specialActions;

    /**
     * @param SpecialActionInterface[] $specialActions
     */
    public function __construct(ManagerInterface $messageManager, Session $session, array $specialActions = [])
    {
        $this->messageManager = $messageManager;
        $this->session = $session;
        $this->specialActions = $specialActions;
        $this->validate();
    }

    public function afterExecute(CreatePost $subject, Redirect $result): Redirect
    {
        $last = $this->messageManager->getMessages()->getLastAddedMessage();
        if ($last && $last->getType() === MessageInterface::TYPE_SUCCESS) {
            $customer = $this->session->getCustomer();
            foreach ($this->specialActions as $specialAction) {
                if ($specialAction->isApplicable($customer)) {
                    $specialAction->execute($result);
                }
            }
        }
        return $result;
    }

    private function validate(): void
    {
        foreach ($this->specialActions as $specialAction) {
            if (!$specialAction instanceof SpecialActionInterface) {
                throw new InvalidArgumentException('Invalid object type.');
            }
        }
    }
}
