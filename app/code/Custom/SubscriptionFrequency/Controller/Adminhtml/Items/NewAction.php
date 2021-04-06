<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

class NewAction extends \Custom\SubscriptionFrequency\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
