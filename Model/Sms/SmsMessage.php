<?php

namespace CodeLands\MobileLogin\Model\Sms;

use Magento\Framework\DataObject;

class SmsMessage extends DataObject implements SmsMessageInterface
{
    protected $storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($data);
        $this->storeManager = $storeManager;
    }

    public function getTo()
    {
        return $this->getData('to');
    }

    public function setTo($to)
    {
        return $this->setData(self::SMS_TO, $to);
    }

    public function getFrom()
    {
        return $this->getData(self::SMS_FROM);
    }

    public function setFrom($from)
    {
        return $this->setData(self::SMS_FROM, $from);
    }

    public function getStore()
    {
        return $this->getData(self::SMS_STORE);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setStore($store)
    {
        if ($store instanceof \Magento\Store\Api\Data\StoreInterface) {
            $store = $store->getId();
        }

        return $this->setData(self::SMS_STORE, $store);
    }

    public function getRawTemplate()
    {
        return $this->getData(self::SMS_RAW_TEMPLATE);
    }

    public function setRawTemplate($rawTemplate)
    {
        return $this->setData(self::SMS_RAW_TEMPLATE, $rawTemplate);
    }

    public function getIsSend()
    {
        return (bool) $this->getData(self::SMS_IS_SEND);
    }

    public function setIsSend($isSend)
    {
        return $this->setData(self::SMS_IS_SEND, $isSend);
    }
}
