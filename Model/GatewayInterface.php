<?php

namespace CodeLands\MobileLogin\Model;

use CodeLands\MobileLogin\Model\Sms\SmsMessageException;
use CodeLands\MobileLogin\Model\Sms\SmsMessageInterface;

interface GatewayInterface
{
    /**
     * @params $forceSync
     * @return bool
     * @throws SmsMessageException
     */
    public function sendMessage($forceSync = false);

    /**
     * @return SmsMessageInterface
     */
    public function getMessage();
}
