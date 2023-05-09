<?php

namespace CodeLands\MobileLogin\Model\Gateway;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Phrase;
use Magento\Store\Model\App\Emulation;
use CodeLands\MobileLogin\Model\Config\ConfigProvider;
use CodeLands\MobileLogin\Model\GatewayInterface;
use CodeLands\MobileLogin\Model\Sms\SmsMessageException;
use CodeLands\MobileLogin\Model\Sms\SmsMessageInterface;
use CodeLands\MobileLogin\Model\SmsLogFactory;
use Psr\Log\LoggerInterface;

abstract class AbstractSmsGateway implements GatewayInterface
{
    protected $client = null;

    protected $message;

    protected $logFactory;

    protected $configProvider;

    protected $appEmulation;

    protected $publisher;

    protected $logger;

    public function __construct(
        SmsMessageInterface $message,
        ?SmsLogFactory $logFactory = null,
        ?ConfigProvider $configProvider = null,
        ?Emulation $appEmulation = null,
        ?PublisherInterface $publisher = null,
        ?LoggerInterface $logger = null
    ) {
        $this->message = $message;
        $this->logFactory = $logFactory ?: ObjectManager::getInstance()->get(SmsLogFactory::class);
        $this->configProvider = $configProvider ?: ObjectManager::getInstance()->get(ConfigProvider::class);
        $this->appEmulation = $appEmulation ?: ObjectManager::getInstance()->get(Emulation::class);
        $this->publisher = $publisher ?: ObjectManager::getInstance()->get(PublisherInterface::class);
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

    abstract protected function _send();

    public function sendMessage($forceSync = false)
    {
        if ($this->configProvider->isDevelop()) {
            $this->message->setIsSend(true);
            $this->saveSuccessLog();
            return true;
        }

        try {
            if ($this->configProvider->isAsync() && ! $forceSync) {
                $this->publisher->publish('sms.notification.sender', $this->message);
            } else {
                $this->_send();
                $this->saveSuccessLog();
            }
            return true;
        } catch (\Exception $e) {
            $this->saveErrorLog($e->getMessage());
            $this->logger->critical('Send SMS Error: ', ['exception' => $e]);
            throw new SmsMessageException(new Phrase('Unable to send sms. Please try again later.'));
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFrom()
    {
        $storeId = $this->getMessage()->getStore();
        return $this->getMessage()->getFrom() ?: $this->configProvider->getTwilioPhoneNumber($storeId);
    }


    protected function saveSuccessLog()
    {
        if ($this->configProvider->isLogSms()) {
            $log = $this->logFactory->create();
            $log->saveLog($this->getMessage());
        }
    }

    protected function saveErrorLog($message)
    {
        if ($this->configProvider->isLogSms()) {
            $log = $this->logFactory->create();
            $log->setData('error_message', $message);
            $log->saveLog($this->getMessage(), false);
        }
    }
}
