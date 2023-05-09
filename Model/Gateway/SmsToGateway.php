<?php

namespace CodeLands\MobileLogin\Model\Gateway;

use Intergo\SmsTo\Credentials\ApiKeyCredential;
use Intergo\SmsTo\Module\Auth\Credential;
use Intergo\SmsTo\Module\Sms\Message\SingleMessage;
use Intergo\SmsTo\Module\Sms\Sms;

class SmsToGateway extends AbstractSmsGateway
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function _send()
    {
        $this->appEmulation->startEnvironmentEmulation($this->message->getStore());
        if (! $this->message->getIsSend()) {
            $to = $this->message->getTo();
            $template = $this->message->getRawTemplate();
            $credentials = $this->generateCredential();
            $sms = new Sms($credentials);
            $message = new SingleMessage();
            $message->setSenderID($this->getSenderId());
            $message->setTo($to)->setMessage($template);
            $sms->send($message);
        }
        $this->appEmulation->stopEnvironmentEmulation();
    }

    private function generateCredential()
    {
        $store = $this->getMessage()->getStore();
        if (is_null($this->client)) {
            $apiKey = $this->configProvider->getSmsToApiKey($store);
            $auth = new Credential(new ApiKeyCredential($apiKey));
            $this->client = $auth->verify();
        }

        return $this->client;
    }

    private function getSenderId()
    {
        $store = $this->getMessage()->getStore();
        return $this->configProvider->getSmsToSenderId($store);
    }
}
