<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

use Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

class Save extends Items
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                // phpcs:ignore
                $model = $this->_objectManager->create('Custom\SubscriptionFrequency\Model\SubscriptionFrequency');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $model->setData($data);
                // phpcs:ignore
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('custom_subscriptionfrequency/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('custom_subscriptionfrequency/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('custom_subscriptionfrequency/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('custom_subscriptionfrequency/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                // phpcs:ignore
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                // phpcs:ignore
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('custom_subscriptionfrequency/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('custom_subscriptionfrequency/*/');
    }
}
