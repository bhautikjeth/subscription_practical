<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Controller\Adminhtml\Items;

class Index extends \Custom\SubscriptionFrequency\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('custom_subscriptionFrequency::test');
        $resultPage->getConfig()->getTitle()->prepend(__('Subscription Frequencies'));
        $resultPage->addBreadcrumb(__('Subscription'), __('Subscription'));
        $resultPage->addBreadcrumb(__('Frequencies'), __('Frequencies'));
        return $resultPage;
    }
}
