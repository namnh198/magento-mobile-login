<?php

namespace CodeLands\MobileLogin\Model\Sms\Queue;

use CodeLands\MobileLogin\Model\Sms\SmsMessageException;
use CodeLands\MobileLogin\Model\Sms\SmsMessageInterface;

class Consumer
{
    protected $gatewayFactory;

    public function __construct(
        \CodeLands\MobileLogin\Model\GatewayFactory $gatewayFactory
    ) {
        $this->gatewayFactory = $gatewayFactory;
    }

    public function process(SmsMessageInterface $smsMessage)
    {
        $gateway = $this->gatewayFactory->create(['message' => $smsMessage]);
        try {
            $gateway->sendMessage(true);
        } catch (SmsMessageException $e) {
        }
    }
}
