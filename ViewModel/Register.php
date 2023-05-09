<?php

namespace CodeLands\MobileLogin\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Register implements ArgumentInterface
{
    private $config;

    public function __construct(\CodeLands\MobileLogin\Model\Config\ConfigProvider $config)
    {
        $this->config = $config;
    }

    public function isRequiredEmail()
    {
        return $this->config->isConfirmationRequired();
    }
}
