<?php

namespace CodeLands\MobileLogin\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Login implements ArgumentInterface
{
    private $config;

    public function __construct(\CodeLands\MobileLogin\Model\Config\ConfigProvider $config)
    {
        $this->config = $config;
    }

    public function getLoginMode()
    {
        return $this->config->getLoginMode();
    }
}
