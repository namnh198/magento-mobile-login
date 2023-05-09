<?php

namespace CodeLands\MobileLogin\Model\ResourceModel\SmsLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \CodeLands\MobileLogin\Model\SmsLog::class,
            \CodeLands\MobileLogin\Model\ResourceModel\SmsLog::class
        );
    }

    public function clearLog()
    {
        $this->getConnection()->truncateTable($this->getMainTable());
    }
}
