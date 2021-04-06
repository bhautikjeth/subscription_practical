<?php

namespace Lucasart\StockStatusLabel\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;

class AddOptionToOrderObserver implements ObserverInterface
{
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }
    // phpcs:ignoreFile
    public function execute(Observer $observer)
    {
//        if ($this->helperData->isModuleEnabled()) {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();
        foreach ($quote->getAllVisibleItems() as $quoteItem) {
            $quoteItems[$quoteItem->getId()] = $quoteItem;
        }

        foreach ($order->getAllVisibleItems() as $orderItem) {
            $quoteItemId = $orderItem->getQuoteItemId();
            if (isset($quoteItems[$quoteItemId])) {
                $quoteItem = $quoteItems[$quoteItemId];
                $additionalOptions = $quoteItem->getOptionByCode('additional_options');
//            if (count($additionalOptions) > 0) {
                $options = $orderItem->getProductOptions();
                if ($additionalOptions) {
                    $options['additional_options'] = $this->serializer->unserialize($additionalOptions->getValue());
                }
                $orderItem->setProductOptions($options);
            }

//            }
        }
        return $this;
//        }
    }
}
