<?php

namespace Lucasart\StockStatusLabel\Observer;

use Custom\SubscriptionFrequency\Model\SubscriptionFrequencyFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Store\Model\StoreManagerInterface;

class CheckoutCartAddObserver implements ObserverInterface
{
    protected $layout;
    protected $storeManager;
    protected $request;
    private $serializer;
    private $subscriptionFrequencyFactory;

    public function __construct(
        StoreManagerInterface $storeManager,
        LayoutInterface $layout,
        RequestInterface $request,
        SerializerInterface $serializer,
        SubscriptionFrequencyFactory $subscriptionFrequencyFactory
    ) {
        $this->layout = $layout;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->subscriptionFrequencyFactory = $subscriptionFrequencyFactory;
    }

    public function execute(EventObserver $observer)
    {
        $schoolCollection = $this->subscriptionFrequencyFactory->create()->getCollection();
        $subscriptionsHtml = [];

        foreach ($schoolCollection as $value) {
            $subscriptionsHtml[$value->getSubscriptionfrequencyId()] = $value->getFrequencyName();
        }

        $item = $observer->getQuoteItem();
        $post = $this->request->getPost();

        $additionalOptions = [];
        if ($additionalOption = $item->getOptionByCode('additional_options')) {
            $additionalOptions = $this->serializer->unserialize($additionalOption->getValue());
        }
        $options = [1 => 'Daily', 2 => 'Weekly', 3 => 'Monthly', 4 => 'Yearly'];
        if ($post['subscription']) {
            $subscription = $post['subscription'];
            $subscription_id = $options[$subscription];
            $additionalOptions[] = [
                'label' => 'Subscription',
                'value' => $subscription_id
            ];

            $additionalOptions[] = [
                'label' => 'SCODE',
                'value' => $subscription
            ];
        } else {
            $additionalOptions[] = [
                'label' => 'Subscription',
                'value' => "onetime"
            ];
        }

        if (count($additionalOptions) > 0) {
            $item->addOption([
                'product_id' => $item->getProductId(),
                'code' => 'additional_options',
                'value' => $this->serializer->serialize($additionalOptions)
            ]);
        }
    }
}
