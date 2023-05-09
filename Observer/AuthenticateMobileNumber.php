<?php

namespace CodeLands\MobileLogin\Observer;

use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AuthenticateMobileNumber implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    private $config;

    /**
     * AuthenticateMobileNumber constructor.
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param \Sparsh\MobileNumberLogin\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \CodeLands\MobileLogin\Model\Config\ConfigProvider $config
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var RequestInterface $request */
        $request = $observer->getEvent()->getRequest();
        $storeId = $this->storeManager->getStore()->getId();
        $isModuleEnabled  = $this->config->isActive($storeId);

        $login = $request->getPost('login');
        if (!empty($login['username']) && filter_var($login['username'], FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if ($isModuleEnabled) {
            $countryCode = $request->getParam('country_code');
            $fullMobileNumber = $request->getParam('full_mobile_number');
            $content = [];
            try {
                $content = $this->jsonDecoder->decode($request->getContent());
            } catch (Exception $ex) {

            }
            $data = new DataObject($content ?: []);
            if (empty($fullMobileNumber)) {
                $fullMobileNumber = $data->getData('full_mobile_number');
            }
            if (empty($countryCode)) {
                $countryCode = $data->getData('country_code');
            }
            $this->registry->register('authenticated_mobile_number', [$countryCode, $fullMobileNumber]);
        }
    }
}
