<?php

namespace CodeLands\MobileLogin\Observer;

use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class RedirectToSmsVerify implements ObserverInterface
{
    protected $accountVerifySms;

    protected $session;

    /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;

    public function __construct(
        \CodeLands\MobileLogin\Api\AccountVerifySmsInterface $accountVerifySms,
        CustomerRegistry $customerRegistry,
        \Magento\Customer\Model\Session $session
    ) {
        $this->accountVerifySms = $accountVerifySms;
        $this->customerRegistry = $customerRegistry;
        $this->session = $session;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $model */
        $model = $observer->getData('model');

        if ($model->getConfirmation() && $this->accountVerifySms->isConfirmationRequired($model)) {
            $url = $model->getStore()->getUrl('customer/account/verifysms', ['id' => $model->getEntityId()]);
            $this->session->setUsername($model->getEmail());
            header('Location: ' . $url);
            exit();
        }
    }
}
