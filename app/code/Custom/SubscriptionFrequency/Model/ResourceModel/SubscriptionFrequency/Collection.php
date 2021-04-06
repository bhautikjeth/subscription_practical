<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Model\ResourceModel\SubscriptionFrequency;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'subscriptionfrequency_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        // phpcs:ignoreFile
        $this->_init(
            'Custom\SubscriptionFrequency\Model\SubscriptionFrequency',
            'Custom\SubscriptionFrequency\Model\ResourceModel\SubscriptionFrequency'
        );
    }
}
