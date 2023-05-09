<?php

namespace CodeLands\MobileLogin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SmsLog extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sms_notification_log', 'id');
    }
}
