<?php

namespace CodeLands\MobileLogin\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Math\Random;

class RegisterMobileNumber implements ObserverInterface
{
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
     * @var Random
     */
    private $random;

    /**
     * AuthenticateMobileNumber constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param \Sparsh\MobileNumberLogin\Helper\Data $helperData
     * @param Random $random
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \CodeLands\MobileLogin\Model\Config\ConfigProvider $config,
        Random $random
    ) {
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->config = $config;
        $this->random = $random;
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

        if ($isModuleEnabled && ! $request->getParam('email')) {
            $this->setRandomEmail($request);
        }
    }

    public function setRandomEmail(RequestInterface $request)
    {
        $params = $request->getParams();
        $email = $request->getParam('mobile_number');
        $email .= $this->random->getRandomString(6, Random::CHARS_DIGITS);
        $email .= '@example.com';
        $params['email'] = $email;
        $params['emailconfirm'] = $email;
        $params['email_auto_generated'] = 1;
        $this->registry->register(
            'register_email_auto_generated',
            [
                'email' => $email,
                'phone' => $request->getParam('full_mobile_number')
            ]
        );
        $request->setParams($params);
    }
}
