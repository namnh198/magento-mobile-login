<?php

namespace CodeLands\MobileLogin\ViewModel;

use CodeLands\MobileLogin\Model\Constant;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class EditAccount implements ArgumentInterface
{
    private $currentCustomer;

    public function __construct(
        CurrentCustomer $currentCustomer
    ) {
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get customer custom mobile number attribute as value.
     *
     * @return string Customer phone number value.
     */
    public function getMobileNumber()
    {
        $mobileNumberAttribute = $this->getCustomer()
            ->getCustomAttribute(Constant::MOBILE_NUMBER);
        return $mobileNumberAttribute ? (string) $mobileNumberAttribute->getValue() : null;
    }

    /**
     * Get customer custom country code attribute as value.
     *
     * @return string Customer phone number value.
     */
    public function getCountryCode()
    {
        $countryCodeAttribute = $this->getCustomer()
            ->getCustomAttribute(Constant::COUNTRY_CODE);
        return $countryCodeAttribute ? (string) $countryCodeAttribute->getValue() : null;
    }
    /**
     * Get customer custom full mobile number attribute as value.
     *
     * @return string Customer phone number value.
     */
    public function getFullMobileNumber()
    {
        $countryCodeAttribute = $this->getCustomer()
            ->getCustomAttribute(Constant::FULL_MOBILE_NUMBER);
        return $countryCodeAttribute ? (string) $countryCodeAttribute->getValue() : null;
    }

    public function getCustomer()
    {
        return $this->currentCustomer->getCustomer();
    }
}
