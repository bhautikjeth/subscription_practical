<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

use Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

class Delete extends Items
{

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                // phpcs:ignore
                $model = $this->_objectManager->create('Custom\SubscriptionFrequency\Model\SubscriptionFrequency');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('custom_subscriptionfrequency/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                // phpcs:ignore
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('custom_subscriptionfrequency/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('custom_subscriptionfrequency/*/');
    }
}
