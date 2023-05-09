<?php

namespace CodeLands\MobileLogin\Model;

use CodeLands\MobileLogin\Api\ResendSmsUrlInterface;

class ResendSmsUrl implements ResendSmsUrlInterface
{
    const ROUTE_ACCOUNT_RESEND_SMS = 'customer/account/resendsmscode';

    protected $json;

    protected $urlBuilder;

    protected $urlEncoder;

    protected $urlDecoder;

    public function __construct(
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Url\EncoderInterface $urlEncode,
        \Magento\Framework\Url\DecoderInterface $urlDecode
    ) {
        $this->json = $json;
        $this->urlBuilder = $urlBuilder;
        $this->urlEncoder = $urlEncode;
        $this->urlDecoder = $urlDecode;
    }

    public function getResendUrl($mobilePhone, $countryCode)
    {
        return $this->urlBuilder->getUrl(
            self::ROUTE_ACCOUNT_RESEND_SMS,
            ['customer' => $this->encodeCustomerUrl($mobilePhone, $countryCode),
                '_secure' => true]
        );
    }

    public function encodeCustomerUrl($mobilePhone, $countryCode)
    {
        $orderPath = $this->json->serialize(["mobile_number" => $mobilePhone, "country_code" => $countryCode]);
        return $this->urlEncoder->encode($orderPath);
    }

    public function decodeCustomerUrl($customerParam)
    {
        return $this->json->unserialize($this->urlDecoder->decode($customerParam));
    }
}
