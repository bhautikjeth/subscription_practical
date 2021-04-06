<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Custom\SubscriptionCron\Cron;

use Custom\SubscriptionsOrders\Model\SubscriptionsOrdersFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;

class UpdateOrderStatus
{
    protected $logger;
    protected $_orderCollectionFactory;
    protected $quoteFactory;
    protected $_order;
    protected $subscriptionsOrdersFactory;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $orderCollectionFactory,
        QuoteFactory $quoteFactory,
        Order $order,
        SubscriptionsOrdersFactory $subscriptionsOrdersFactory
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->quoteFactory = $quoteFactory;
        $this->logger = $logger;
        $this->_order = $order;
        $this->subscriptionsOrdersFactory = $subscriptionsOrdersFactory;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {

//        $discount = 10;
//        $collection = $this->_orderCollectionFactory->create()
//            ->addAttributeToSelect('*');
//        $finalArray = [];
//        foreach ($collection->getData() as $key => $value) {
//            $order = $this->_order->load($value['entity_id']);
//            $orderAllItems = $order->getAllItems();
//
//            $frequency_options = [1 => 'Daily', 2 => 'Weekly', 3 => 'Monthly', 4 => 'Yearly'];
//            if ($order->getStatus() === "complete") {
//                foreach ($orderAllItems as $item) {
//                    $options = $item->getProductOptions();
//                    if (isset($options['info_buyRequest']['subscription'])) {
//                        $created_date = $item->getCreatedAt();
//                        $today = "2021-03-29";
//                        $dateArray = [];
//                        while (strtotime($created_date) <= strtotime($today)) {
//                            $dateArray[] = date('Y-m-d', strtotime($created_date));
//                            if ($frequency_options[$options['info_buyRequest']['subscription']] == "Daily") {
//                                $created_date = date("Y-m-d", strtotime("+1 day", strtotime($created_date)));
//                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Weekly") {
//                                $created_date = date("Y-m-d", strtotime("+1 week", strtotime($created_date)));
//                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Monthly") {
//                                $created_date = date("Y-m-d", strtotime("+1 month", strtotime($created_date)));
//                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Yearly") {
//                                $created_date = date("Y-m-d", strtotime("+1 year", strtotime($created_date)));
//                            }
//                        }
//
//                        if (in_array($today, $dateArray)) {
////                            echo "in_array";
////                            echo '<br>';
////                            echo "-----------------END-----------------";
////                            echo '<br>';
////                            exit;
//                            $finalArray[$order->getEntityId()]['customer_id'] = $order->getCustomerId();
//                            $finalArray[$order->getEntityId()]['customer_name'] = $order->getCustomerName();
//                            $finalArray[$order->getEntityId()]['order_id'] = $order->getEntityId();
//                            $finalArray[$order->getEntityId()]['product_id'] = $item->getProductId();
//                            $finalArray[$order->getEntityId()]['product_name'] = $item->getProduct()->getName();
//                            $finalArray[$order->getEntityId()]['price'] = $item->getProduct()->getPrice();
//                            if ($item->getProduct()->getPrice()) {
//                                $discount_value = ($item->getProduct()->getPrice() / 100) * $discount;
//                                $new_price = $item->getProduct()->getPrice() - $discount_value;
//                                $finalArray[$order->getEntityId()]['price_discount'] = $new_price;
//                            } else {
//                                $finalArray[$order->getEntityId()]['price_discount'] = 0;
//                            }
//                            if ($item->getProduct()->getSpecialPrice()) {
//                                $discount_value = ($item->getProduct()->getSpecialPrice() / 100) * $discount;
//                                $new_price = $item->getProduct()->getSpecialPrice() - $discount_value;
//                                $finalArray[$order->getEntityId()]['special_price_discount'] = $new_price;
//                            } else {
//                                $finalArray[$order->getEntityId()]['special_price_discount'] = 0;
//                            }
//                            $finalArray[$order->getEntityId()]['special_price'] = $item->getProduct()->getSpecialPrice();
//                            $finalArray[$order->getEntityId()]['sku'] = $item->getSku();
//                            $finalArray[$order->getEntityId()]['order_date'] = $item->getCreatedAt();
//                            $finalArray[$order->getEntityId()]['subscription_type'] = $options['info_buyRequest']['subscription_type'];
//                            $finalArray[$order->getEntityId()]['subscription'] = $options['info_buyRequest']['subscription'];
//                            $finalArray[$order->getEntityId()]['subscription_start'] = $item->getCreatedAt();
//                            $finalArray[$order->getEntityId()]['payment_method'] = "Cash On Delivery";
//                            $finalArray[$order->getEntityId()]['discount'] = $discount;
//                            $finalArray[$order->getEntityId()]['created_at'] = date('Y-m-d H:i:s');
//                            $finalArray[$order->getEntityId()]['payment_method'] = "Cash On Delivery";
//                            $finalArray[$order->getEntityId()]['status'] = 5;
//                            $model = $this->_objectManager->create('Custom\SubscriptionsOrders\Model\SubscriptionsOrders');
//                            $model->load('1');
//
//                            $today_start = $today . " 00:00:01";
//                            $today_end = $today . " 23:59:59";
//                            $schoolCollection = $this->subscriptionsOrdersFactory->create()->getCollection()->addFieldToFilter('order_id', $order->getEntityId())->addFieldToFilter('created_at', ['from' => $today_start, 'to' => $today_end]);
//                            if ($schoolCollection->getSize() > 0) {
//                            } else {
//                                $model->setData($finalArray[$order->getEntityId()]);
//                                $model->save();
//                            }
//                        }
//                    }
//                }
//            }
//        }
    }
}
