<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

use Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

class Edit extends Items
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        // phpcs:ignore
        $model = $this->_objectManager->create('Custom\SubscriptionFrequency\Model\SubscriptionFrequency');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('custom_subscriptionfrequency/*');
                return;
            }
        }
        // set entered data if was error when we do save
        // phpcs:ignore
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_custom_subscriptionfrequency_items', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('items_items_edit');
        $this->_view->renderLayout();
    }
}
