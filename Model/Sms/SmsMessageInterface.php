<?php

namespace CodeLands\MobileLogin\Model\Sms;

interface SmsMessageInterface
{
    const SMS_TO = 'to';
    const SMS_FROM = 'from';
    const SMS_STORE = 'store';
    const SMS_RAW_TEMPLATE = 'raw_template';
    const SMS_IS_SEND = 'is_send';

    /**
     * @return string
     */
    public function getTo();

    /**
     * @param $to
     * @return SmsMessageInterface
     */
    public function setTo($to);

    /**
     * @return string
     */
    public function getFrom();

    /**
     * @param $from
     * @return SmsMessageInterface
     */
    public function setFrom($from);

    /**
     * @return int
     */
    public function getStore();

    /**
     * @param $store
     * @return SmsMessageInterface
     */
    public function setStore($store);

    /**
     * @return string
     */
    public function getRawTemplate();

    /**
     * @param $rawTemplate
     * @return SmsMessageInterface
     */
    public function setRawTemplate($rawTemplate);

    /**
     * @return bool
     */
    public function getIsSend();

    /**
     * @param $isSend
     * @return SmsMessageInterface
     */
    public function setIsSend($isSend);
}
