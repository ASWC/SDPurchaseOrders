<?php
namespace SDMagentoModules\SDPurchaseOrder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_payment'),
            'po_filename',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'PO file uploaded by user at checkout',
                'after' => 'po_number'
            ]
        );
        $setup->endSetup();
    }
}