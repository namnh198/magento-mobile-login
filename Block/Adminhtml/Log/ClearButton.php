<?php

namespace CodeLands\MobileLogin\Block\Adminhtml\Log;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ClearButton implements ButtonProviderInterface
{
    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * ClearButton constructor.
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Clear All Logs'),
            'class'      => 'primary',
            'on_click'   => 'deleteConfirm(\'' . __(
                'Are you sure you want to clear all email logs?'
            ) . '\', \'' . $this->_urlBuilder->getUrl('*/*/clear') . '\')',
            'sort_order' => 10,
        ];
    }
}
