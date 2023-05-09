<?php

namespace CodeLands\MobileLogin\Controller\Adminhtml\SmsLog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'CodeLands_MobileLogin::sms_log';

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('CodeLands_MobileLogin::sms_log');
        $resultPage->getConfig()->getTitle()->prepend(__('Sms Log'));
        return $resultPage;
    }
}
