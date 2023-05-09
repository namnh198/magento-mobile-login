<?php

namespace CodeLands\MobileLogin\Observer;

use Aws\Sms\Exception\SmsException;
use CodeLands\MobileLogin\Api\AccountVerifySmsInterface;
use CodeLands\MobileLogin\Api\ResendSmsUrlInterface;
use CodeLands\MobileLogin\Model\Constant;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Message\ManagerInterface;

class SendSmdAndRedirectVerify implements ObserverInterface
{
    protected $accountVerifySms;

    protected $session;

    protected $customerFactory;

    protected $resendSmsUrl;

    protected $messageManager;

    /**
     * @var CustomerRegistry
     */
    protected $customerRegistry;

    public function __construct(
        CustomerFactory $customerFactory,
        AccountVerifySmsInterface $accountVerifySms,
        ResendSmsUrlInterface $resendSmsUrl,
        ManagerInterface $messageManager,
        CustomerRegistry $customerRegistry,
        Session $session
    ) {
        $this->customerFactory = $customerFactory;
        $this->accountVerifySms = $accountVerifySms;
        $this->resendSmsUrl = $resendSmsUrl;
        $this->messageManager = $messageManager;
        $this->customerRegistry = $customerRegistry;
        $this->session = $session;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getData('customer');
        $model = $this->customerFactory->create()->updateData($customer);

        try {
            if ($model->getConfirmation() && $this->accountVerifySms->isConfirmationRequired($model)) {
                $url = $model->getStore()->getUrl('customer/account/verifysms', ['id' => $model->getEntityId()]);
                $this->session->setUsername($model->getEmail());
                header('Location: ' . $url);
                exit();
            }
        } catch (InvalidTransitionException | SmsException $e) {
            $this->messageManager->addExceptionMessage($e);
            $mobileNumber = $customer->getCustomAttribute(Constant::MOBILE_NUMBER)->getValue();
            $countryCode = $customer->getCustomAttribute(Constant::COUNTRY_CODE)->getValue();
            $url = $this->resendSmsUrl->getResendUrl($mobileNumber, $countryCode);
            header('Location: ' . $url);
            exit();
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addExceptionMessage($e);
        }
    }
}
