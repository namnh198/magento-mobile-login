<?php

namespace CodeLands\MobileLogin\Api;

interface AccountVerifySmsInterface
{
    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @return bool
     */
    public function isConfirmationRequired(\Magento\Customer\Model\Customer $customer);

    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @return bool
     * @throws \Magento\Framework\Exception\State\InvalidTransitionException
     * @throws \OpenTechiz\SmsNotification\Model\Sms\SmsMessageException
     */
    public function sendVerify(\Magento\Customer\Model\Customer $customer);
}
