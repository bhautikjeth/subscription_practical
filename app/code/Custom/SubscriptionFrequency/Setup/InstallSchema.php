<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Creating table custom_subscriptionfrequency
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('custom_subscriptionfrequency')
        )->addColumn(
            'subscriptionfrequency_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            'frequency_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Frequency Name'
        )->addColumn(
            'status',
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            'Status'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false],
            'Created At'
        )->setComment(
            'Custom SubscriptionFrequency Table'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
