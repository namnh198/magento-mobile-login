<?php

namespace CodeLands\MobileLogin\Controller\Adminhtml\SmsLog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use CodeLands\MobileLogin\Model\ResourceModel\SmsLog\CollectionFactory;

class Clear extends Action
{
    const ADMIN_RESOURCE = 'CodeLands_MobileLogin::sms_log';

    protected $collectionLog;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionLog
     * @param Context $context
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionLog
    ) {
        $this->collectionLog = $collectionLog;

        parent::__construct($context);
    }

    /**
     * Clear Emails Log
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \CodeLands\MobileLogin\Model\ResourceModel\SmsLog\Collection $collection */
        $collection = $this->collectionLog->create();
        try {
            $collection->clearLog();
            $this->messageManager->addSuccess(__('Success'));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
