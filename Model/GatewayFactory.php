<?php

namespace CodeLands\MobileLogin\Model;

class GatewayFactory
{
    protected $objectManager = null;

    protected $configProvider = null;

    protected $gateways = [];

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \CodeLands\MobileLogin\Model\Config\ConfigProvider $configProvider,
        array $gateways = []
    ) {
        $this->objectManager = $objectManager;
        $this->configProvider = $configProvider;
        $this->gateways = $gateways;
    }

    public function create(array $data = [])
    {
        $gateway = $this->configProvider->getSmsGateway() ?: 'twilio';

        if (! array_key_exists($gateway, $this->gateways)) {
            throw new \LogicException("$gateway Gateway SMS doesn't exists");
        }
        $instance = $this->gateways[$gateway];
        $instance = $this->objectManager->create($instance, $data);

        if (! $instance instanceof GatewayInterface) {
            throw new \UnexpectedValueException(
                'Class ' . get_class($instance) . ' should be an instance of \CodeLands\MobileLogin\Model\GatewayInterface'
            );
        }

        return $instance;
    }
}
