<?php

namespace CodeLands\MobileLogin\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
{
    const XPATH_GENERAL_ASYNC = 'sms_notification/general/async_all';
    const XPATH_GENERAL_LOG_SMS = 'sms_notification/general/log_sms';
    const XPATH_GENERAL_DEVELOP = 'sms_notification/general/develop';
    const XPATH_CONFIGURATION_SMS_GATEWAY = 'sms_notification/configuration/gate_way';
    const XPATH_CONFIGURATION_TWILIO_ACCOUNT_SID = 'sms_notification/configuration/twilio_sid';
    const XPATH_CONFIGURATION_TWILIO_AUTH_TOKEN = 'sms_notification/configuration/twilio_token';
    const XPATH_CONFIGURATION_TWILIO_PHONE_NUMBER = 'sms_notification/configuration/twilio_phone_number';
    const XPATH_CONFIGURATION_SMS_TO_SENDER_ID = 'sms_notification/configuration/sms_to_sender_id';
    const XPATH_CONFIGURATION_SMS_TO_API_KEY = 'sms_notification/configuration/sms_to_api_key';
    const XPATH_SMS_TEMPLATE_ORDER_SUCCESS = 'sms_notification/sms_template/order_success';
    const XPATH_SMS_TEMPLATE_VERIFY_ACCOUNT = 'sms_notification/sms_template/verify_account';
    const XPATH_SMS_TEMPLATE_PASSWORD_RESET = 'sms_notification/sms_template/password_reset';
    const XPATH_MOBILE_LOGIN_ENABLE = 'mobile_login/general/enable';
    const XPATH_MOBILE_LOGIN_LOGIN_MODE = 'mobile_login/general/login_mode';
    const XPATH_MOBILE_LOGIN_CONFIRMED = 'mobile_login/general/enable_confirmed';
    const XPATH_MOBILE_LOGIN_OTP_LENGTH = 'mobile_login/general/otp_length';

    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isActive($storeId = null)
    {
        return $this->isSetFlag(self::XPATH_MOBILE_LOGIN_ENABLE, $storeId);
    }

    public function getLoginMode($storeId = null)
    {
        return $this->getValue(self::XPATH_MOBILE_LOGIN_LOGIN_MODE, $storeId);
    }
    public function isConfirmationRequired($storeId = null)
    {
        return $this->isSetFlag(self::XPATH_MOBILE_LOGIN_CONFIRMED, $storeId);
    }

    public function getOtpLength($storeId = null)
    {
        return $this->getValue(self::XPATH_MOBILE_LOGIN_OTP_LENGTH, $storeId);
    }

    public function isAsync($storeId = null)
    {
        return $this->isSetFlag(self::XPATH_GENERAL_ASYNC, $storeId);
    }

    public function isDevelop($storeId = null)
    {
        return $this->isSetFlag(self::XPATH_GENERAL_DEVELOP, $storeId);
    }

    public function isLogSms($storeId = null)
    {
        return $this->isSetFlag(self::XPATH_GENERAL_LOG_SMS, $storeId);
    }

    public function getSmsGateway($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_SMS_GATEWAY, $storeId);
    }

    public function getTwilioAccountSID($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_TWILIO_ACCOUNT_SID, $storeId);
    }

    public function getTwilioAuthToken($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_TWILIO_AUTH_TOKEN, $storeId);
    }

    public function getTwilioPhoneNumber($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_TWILIO_PHONE_NUMBER, $storeId);
    }

    public function getSmsToSenderId($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_SMS_TO_SENDER_ID, $storeId);
    }

    public function getSmsToApiKey($storeId = null)
    {
        return $this->getValue(self::XPATH_CONFIGURATION_SMS_TO_API_KEY, $storeId);
    }

    public function getTemplateOrderSuccess($storeId = null)
    {
        return $this->getValue(self::XPATH_SMS_TEMPLATE_ORDER_SUCCESS, $storeId);
    }

    public function getTemplateVerifySms($storeId = null)
    {
        return $this->getValue(self::XPATH_SMS_TEMPLATE_VERIFY_ACCOUNT, $storeId);
    }

    public function getTemplateResetPassword($storeId = null)
    {
        return $this->getValue(self::XPATH_SMS_TEMPLATE_PASSWORD_RESET, $storeId);
    }

    protected function getValue($path, $storeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scope, $storeId);
    }

    protected function isSetFlag($path, $storeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag($path, $scope, $storeId);
    }
}
