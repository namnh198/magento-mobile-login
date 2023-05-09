<?php

namespace CodeLands\MobileLogin\Model;

use CodeLands\MobileLogin\Api\AccountVerifySmsInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Phrase;
use CodeLands\MobileLogin\Model\Sms\SmsMessageException;

class AccountVerifySms implements AccountVerifySmsInterface
{
    protected $registry;

    protected $storeManager;

    protected $accountManagement;

    protected $mathRandom;

    protected $addressRegistry;

    protected $customerRepository;

    protected $config;

    protected $smsConfigProvider;

    protected $smsGateWayFactory;

    protected $smsMessageInterfaceFactory;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Customer\Model\AddressRegistry $addressRegistry,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        \CodeLands\MobileLogin\Model\Config\ConfigProvider $config,
        \CodeLands\MobileLogin\Model\GatewayFactory $gatewayFactory,
        \CodeLands\MobileLogin\Model\Sms\SmsMessageInterfaceFactory $smsMessageInterfaceFactory
    ) {
        $this->registry = $registry;
        $this->accountManagement = $accountManagement;
        $this->storeManager = $storeManager;
        $this->mathRandom = $mathRandom;
        $this->addressRegistry = $addressRegistry;
        $this->customerRepository = $customerRepository;
        $this->customerRegistry = $customerRegistry;
        $this->config = $config;
        $this->smsGateWayFactory = $gatewayFactory;
        $this->smsMessageInterfaceFactory = $smsMessageInterfaceFactory;
    }

    public function isConfirmationRequired(\Magento\Customer\Model\Customer $customer)
    {
        if (! $this->config->isActive($customer->getStoreId())) {
            return false;
        }

        if (! $customer->getData('mobile_number')) {
            return false;
        }

        return $this->config->isConfirmationRequired($customer->getStoreId());
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @return bool
     * @throws InvalidTransitionException|SmsMessageException
     */
    public function sendVerify(\Magento\Customer\Model\Customer $customer)
    {
        if (!$customer->getConfirmation()) {
            throw new InvalidTransitionException(__("Confirmation isn't needed."));
        }

        try {
            $to = $customer->getData('full_mobile_number');
            $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
            $code = $customerSecure->getConfirmationVerifyCode();
            if (!$code) {
                $code = $customerSecure->getConfirmation();
            }
            $store = $this->storeManager->getStore();
            $template = $this->getTemplateVerify($code, $store->getStoreId());

            $messageData['data'] = [
                'to' => $to,
                'store' => $store->getStoreId(),
                'raw_template' => $template
            ];

            $message = $this->smsMessageInterfaceFactory->create($messageData);
            $this->smsGateWayFactory->create(['message' => $message])->sendMessage();
            return true;
        } catch (\Exception $e) {
            throw new SmsMessageException(new Phrase($e->getMessage()));
        }
    }

    /**
     * @throws NoSuchEntityException
     * @throws SmsMessageException
     * @throws LocalizedException
     */
    public function initiatePasswordReset(\Magento\Customer\Model\Customer $customer, $websiteId = null)
    {
        $store = $this->storeManager->getStore();
        if ($websiteId === null) {
            $websiteId = $store->getWebsiteId();
        }

        $to = $customer->getData('full_mobile_number');
        $newPasswordToken = $this->mathRandom->getUniqueHash();
        // load customer by email
        $customerData = $this->customerRepository->get($customer->getEmail(), $websiteId);
        $this->disableAddressValidation($customerData);
        $this->accountManagement->changeResetPasswordLinkToken($customerData, $newPasswordToken);
        $customerSecure = $this->customerRegistry->retrieveSecureData($customerData->getId());

        $url = $store->getUrl('customer/account/createPassword/', ['_query' => ['token' => $customerSecure->getRpToken(), 'id' => $customerData->getId()], '_nosid' => 1]);

        $params = [
            '{{resetUrl}}' => $url,
            '{{otp_code}}' => $customerSecure->getRpVerifyCode(),
            '{{token}}' => $customerSecure->getRpToken(),
        ];
        $messageData['data'] = [
            'to' => $to,
            'store' => $store->getStoreId(),
            'raw_template' => $this->getTemplatePasswordReset($params, $store->getStoreId())
        ];

        $message = $this->smsMessageInterfaceFactory->create($messageData);
        $this->smsGateWayFactory->create(['message' => $message])->sendMessage();
    }

    protected function _renderTemplatePlaceholder($template, $params = [])
    {
        foreach ((array) $params as $key => $val) {
            $template = str_replace($key, $val, $template);
        }
        return $template;
    }

    protected function getTemplateVerify($code, $storeId = null)
    {
        $template = $this->config->getTemplateVerifySms($storeId);

        return $this->_renderTemplatePlaceholder($template, ['{{otp_code}}' => $code]);
    }

    protected function getTemplatePasswordReset($params, $storeId = null)
    {
        $template = $this->config->getTemplateResetPassword($storeId);

        return $this->_renderTemplatePlaceholder($template, $params);
    }

    /**
     * @throws NoSuchEntityException
     */
    private function disableAddressValidation($customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }
}
