<?php

namespace CodeLands\MobileLogin\Model\Gateway;

use Twilio\Rest\Client as TwilioClient;

class TwilioSmsGateway extends AbstractSmsGateway
{
    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    protected function _send()
    {
        $this->appEmulation->startEnvironmentEmulation($this->message->getStore());
        if (! $this->message->getIsSend()) {
            $to = $this->message->getTo();
            $template = $this->message->getRawTemplate();
            $from = $this->getFrom();
            $this->generateTwilio()->messages->create($to, [
                'body' => $template,
                'from' => $from
            ]);
        }
        $this->appEmulation->stopEnvironmentEmulation();
    }

    /**
     * @return TwilioClient
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    private function generateTwilio()
    {
        $store = $this->getMessage()->getStore();
        if (is_null($this->client)) {
            $authId = $this->configProvider->getTwilioAccountSID($store);
            $authToken = $this->configProvider->getTwilioAuthToken($store);
            $this->client = new TwilioClient($authId, $authToken);
        }

        return $this->client;
    }
}
