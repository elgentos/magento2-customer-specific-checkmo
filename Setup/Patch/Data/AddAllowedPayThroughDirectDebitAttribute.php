<?php

declare(strict_types=1);

namespace Elgentos\CustomerSpecificCheckMo\Setup\Patch\Data;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Attribute\Backend\Data\Boolean;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddAllowedPayThroughDirectDebitAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;

    private EavSetupFactory $eavSetupFactory;

    private Config $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        Config $config
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->config          = $config;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply(): AddAllowedPayThroughDirectDebitAttribute
    {
        $setup = $this->moduleDataSetup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Customer::ENTITY,
            'allowed_pay_through_checkmo',
            [
                'type' => 'int',
                'label' => 'Customer is allowed to pay through checkmo',
                'input' => 'boolean',
                'required' => false,
                'backend' => Boolean::class,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]
        );

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            'allowed_pay_through_checkmo'
        );


        $attribute = $this->config->getAttribute(
            Customer::ENTITY,
            'allowed_pay_through_checkmo'
        );

        $attribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );

        $attribute->save();

        $setup->endSetup();

        return $this;
    }

    public function revert()
    {
        $setup = $this->moduleDataSetup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(
            Customer::ENTITY,
            'allowed_pay_through_checkmo'
        );

        $setup->endSetup();
    }
}
