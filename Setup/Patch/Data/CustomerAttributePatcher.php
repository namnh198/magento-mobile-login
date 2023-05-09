<?php

namespace CodeLands\MobileLogin\Setup\Patch\Data;

use CodeLands\MobileLogin\Model\Constant;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Config;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CustomerAttributePatcher implements DataPatchInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * AccountPurposeCustomerAttribute constructor.
     * @param ModuleDataSetupInterface $setup
     * @param Config $eavConfig
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Config $eavConfig,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->setup = $setup;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @throws \Zend_Validate_Exception
     * @throws LocalizedException
     * @throws \Exception
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->setup]);

        $customerSetup->addAttribute(Customer::ENTITY, Constant::MOBILE_NUMBER, [
            'label' => 'Mobile Number',
            'input' => 'text',
            'backend' => \CodeLands\MobileLogin\Model\Attribute\Backend\MobileNumber::class,
            'required' => false,
            'sort_order' => 85,
            'position' => 85,
            'system' => false,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true
        ]);

        /** @var $attribute */
        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, Constant::MOBILE_NUMBER);

        $usedInForms = [
            'adminhtml_customer',
            'checkout_register',
            'customer_account_create',
            'customer_account_edit',
            'adminhtml_checkout'
        ];

        $attribute->setData('used_in_forms', $usedInForms);
        $attribute->save();

        $customerSetup->addAttribute(Customer::ENTITY, Constant::COUNTRY_CODE, [
            'label' => 'Country Code',
            'input' => 'text',
            'required' => false,
            'sort_order' => 84,
            'position' => 84,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false
        ]);

        /** @var $attribute */
        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, Constant::COUNTRY_CODE);

        $attribute->setData('used_in_forms', $usedInForms);
        $attribute->save();

        $customerSetup->addAttribute(Customer::ENTITY, Constant::FULL_MOBILE_NUMBER, [
            'label' => 'Full Phone Number',
            'input' => 'text',
            'required' => false,
            'sort_order' => 85,
            'position' => 85,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, Constant::FULL_MOBILE_NUMBER);
        $attribute->setData('used_in_forms', $usedInForms);
        $attribute->save();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
