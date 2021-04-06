<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Model\ResourceModel;

class SubscriptionFrequency extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('custom_subscriptionfrequency', 'subscriptionfrequency_id');
        /*here "custom_subscriptionfrequency" is table name and "subscriptionfrequency_id" is
        the primary key of custom table*/
    }
}
