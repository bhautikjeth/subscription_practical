<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Model;

use Magento\Framework\Model\AbstractModel;

class SubscriptionFrequency extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        // phpcs:ignore
        $this->_init('Custom\SubscriptionFrequency\Model\ResourceModel\SubscriptionFrequency');
    }
}
