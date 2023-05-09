<?php
namespace CodeLands\MobileLogin\Plugin\Model;

use CodeLands\MobileLogin\Model\Constant;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Math\Random;

class AccountManagement
{
    private $customerCollectionFactory;

    private $registry;

    private $config;

    private $random;

    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\Registry $registry,
        \CodeLands\MobileLogin\Model\Config\ConfigProvider $config,
        Random $random
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->registry = $registry;
        $this->config = $config;
        $this->random = $random;
    }

    /**
     * @throws InvalidEmailOrPasswordException
     */
    public function beforeAuthenticate(
        \Magento\Customer\Model\AccountManagement $subject,
        $username,
        $password
    ) {
        $authenticated =  $this->registry->registry('authenticated_mobile_number');

        if ($this->config->isActive() && is_numeric($username) && is_array($authenticated) && count($authenticated) > 1) {
            $customerCollection = $this->customerCollectionFactory->create();
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $customerCollection->addFieldToSelect('*')
                ->addFieldToFilter(Constant::COUNTRY_CODE, $authenticated[0])
                ->addFieldToFilter(Constant::FULL_MOBILE_NUMBER, $authenticated[1])
                ->getLastItem();

            if (! $customer || ! $customer->getEmail()) {
                throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
            }

            $username = $customer->getEmail();
        }

        return [$username, $password];
    }
}
