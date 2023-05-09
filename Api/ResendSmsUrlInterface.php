<?php

namespace CodeLands\MobileLogin\Api;

interface ResendSmsUrlInterface
{
    public function getResendUrl($mobilePhone, $countryCode);

    public function encodeCustomerUrl($mobilePhone, $countryCode);

    public function decodeCustomerUrl($customerParam);
}
