<?php

namespace CodeLands\MobileLogin\Model\Config\Source;

class SmsGateway implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            '' => __('Select Provider'),
            'twilio' => __('Twilio'),
            'sms_to' => __('SMS.to')
        ];
    }
}
