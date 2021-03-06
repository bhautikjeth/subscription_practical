<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Custom\SubscriptionCron\Cron;

use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;
use Custom\SubscriptionFrequency\Model\SubscriptionFrequencyFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Result\PageFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\Service\OrderService;
use Magento\Store\Model\StoreManagerInterface;

class UpdateOrderStatus
{
    protected $_pageFactory;
    protected $_orderCollectionFactory;
    protected $quote;
    protected $_order;
    protected $storeManager;
    protected $_product;
    protected $_formkey;
    protected $quoteManagement;
    protected $customerFactory;
    protected $customerRepository;
    protected $orderService;
    protected $orderSender;
    protected $subscriptionFrequencyFactory;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        QuoteFactory $quote,
        Order $order,
        StoreManagerInterface $storeManager,
        Product $product,
        FormKey $formkey,
        QuoteManagement $quoteManagement,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderService $orderService,
        OrderSender $orderSender,
        ProductRepositoryInterface $productRepository,
        SubscriptionFrequencyFactory $subscriptionFrequencyFactory
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->quote = $quote;
        $this->_order = $order;
        $this->storeManager = $storeManager;
        $this->_product = $product;
        $this->_formkey = $formkey;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        $this->orderSender = $orderSender;
        $this->productRepository = $productRepository;
        $this->subscriptionFrequencyFactory = $subscriptionFrequencyFactory;
    }

    /**
     * Execute the cron
     *
     * @return void
     **/
    // phpcs:ignoreFile
    public function execute()
    {
        $discount = 10;

        $today = date('Y-m-d');
        $today = date("Y-m-d", strtotime("+1 day", strtotime($today)));
        $collection = $this->_orderCollectionFactory->create()
            ->addAttributeToSelect('*')->addFieldToFilter('created_at', ['to' => $today]);
        $finalArray = [];

        foreach ($collection->getData() as $key => $value) {
            $order = $this->_order->load($value['entity_id']);
            $orderAllItems = $order->getAllItems();

            $schoolCollection = $this->subscriptionFrequencyFactory->create()->getCollection();
            $frequency_options = [];

            foreach ($schoolCollection as $value) {
                $frequency_options[$value->getSubscriptionfrequencyId()] = $value->getFrequencyName();
            }

            foreach ($orderAllItems as $item) {
                if ($item->getProductType() == "simple") {
                    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cronorders.log');
                    $logger = new \Zend\Log\Logger();
                    $logger->addWriter($writer);

                    $options = $item->getProductOptions();
                    if (isset($options['info_buyRequest']['subscription'])) {
                        $created_date = $item->getCreatedAt();
                        $today = date('Y-m-d');
//                        echo '<br>';
                        // $created_date = date("Y-m-d", strtotime("-1 day", strtotime($created_date)));
                        // echo $today;
                        $dateArray = [];
                        while (strtotime($created_date) <= strtotime($today)) {
                            $dateArray[] = date('Y-m-d', strtotime($created_date));
                            if ($frequency_options[$options['info_buyRequest']['subscription']] == "Daily") {
                                $created_date = date(
                                    "Y-m-d",
                                    strtotime("+1 day", strtotime($created_date))
                                );
                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Weekly") {
                                $created_date = date(
                                    "Y-m-d",
                                    strtotime("+1 week", strtotime($created_date))
                                );
                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Monthly") {
                                $created_date = date(
                                    "Y-m-d",
                                    strtotime("+1 month", strtotime($created_date))
                                );
                            } elseif ($frequency_options[$options['info_buyRequest']['subscription']] == "Yearly") {
                                $created_date = date(
                                    "Y-m-d",
                                    strtotime("+1 year", strtotime($created_date))
                                );
                            }
                        }
                        if (in_array($today, $dateArray)) {
                            $finalArray[$order->getEntityId()]['customer_id']
                                = $order->getCustomerId();
                            $finalArray[$order->getEntityId()]['email'] = $order->getCustomerEmail();
                            $finalArray[$order->getEntityId()]['shipping_address']['firstname']
                                = $order->getShippingAddress()->getFirstname();
                            $finalArray[$order->getEntityId()]['shipping_address']['lastname']
                                = $order->getShippingAddress()->getLastname();
                            $finalArray[$order->getEntityId()]['shipping_address']['city']
                                = $order->getShippingAddress()->getCity();
                            $finalArray[$order->getEntityId()]['shipping_address']['country_id']
                                = $order->getShippingAddress()->getCountryId();
                            $finalArray[$order->getEntityId()]['shipping_address']['street']
                                = $order->getShippingAddress()->getStreet();
                            $finalArray[$order->getEntityId()]['shipping_address']['region']
                                = $order->getShippingAddress()->getRegion();
                            $finalArray[$order->getEntityId()]['shipping_address']['region_id']
                                = $order->getShippingAddress()->getRegionId();
                            $finalArray[$order->getEntityId()]['shipping_address']['postcode']
                                = $order->getShippingAddress()->getPostcode();
                            $finalArray[$order->getEntityId()]['shipping_address']['telephone']
                                = $order->getShippingAddress()->getTelephone();
                            $finalArray[$order->getEntityId()]['shipping_address']['fax']
                                = $order->getShippingAddress()->getFax();
                            $finalArray[$order->getEntityId()]['billing_address'] = $order->getBillingAddress();
                            $finalArray[$order->getEntityId()]['items'] = $order->getItems();
                            $finalArray[$order->getEntityId()]['customer_name'] = $order->getCustomerName();
                            $finalArray[$order->getEntityId()]['order_id'] = $order->getEntityId();
                            $finalArray[$order->getEntityId()]['product_id'] = $item->getProductId();
                            $finalArray[$order->getEntityId()]['super'] = $item->getProductId();
                            $finalArray[$order->getEntityId()]['product_name'] = $item->getProduct()->getName();
                            $finalArray[$order->getEntityId()]['price'] = $item->getProduct()->getPrice();
                            if ($item->getProduct()->getPrice()) {
                                $discount_value = ($item->getProduct()->getPrice() / 100) * $discount;
                                $new_price = $item->getProduct()->getPrice() - $discount_value;
                                $finalArray[$order->getEntityId()]['price_discount'] = $new_price;
                            } else {
                                $finalArray[$order->getEntityId()]['price_discount'] = 0;
                            }
                            if ($item->getProduct()->getSpecialPrice()) {
                                $discount_value = ($item->getProduct()->getSpecialPrice() / 100) * $discount;
                                $new_price = $item->getProduct()->getSpecialPrice() - $discount_value;
                                $finalArray[$order->getEntityId()]['special_price_discount'] = $new_price;
                            } else {
                                $finalArray[$order->getEntityId()]['special_price_discount'] = 0;
                            }
                            $finalArray[$order->getEntityId()]['special_price']
                                = $item->getProduct()->getSpecialPrice();
                            $finalArray[$order->getEntityId()]['sku'] = $item->getSku();
                            $finalArray[$order->getEntityId()]['order_date'] = $item->getCreatedAt();
                            $finalArray[$order->getEntityId()]['subscription_type']
                                = $options['info_buyRequest']['subscription_type'];
                            $finalArray[$order->getEntityId()]['subscription']
                                = $options['info_buyRequest']['subscription'];
                            $finalArray[$order->getEntityId()]['subscription_start'] = $item->getCreatedAt();
                            $finalArray[$order->getEntityId()]['discount'] = $discount;
                            $finalArray[$order->getEntityId()]['created_at'] = $today . date('H:i:s');
                            $orderInfo = [
                                'email' => $finalArray[$order->getEntityId()]['email'], //customer email id
                                'currency_id' => 'USD',
                                'address' => [
                                    'firstname' => $finalArray[$order->getEntityId()]['shipping_address']['firstname'],
                                    'lastname' => $finalArray[$order->getEntityId()]['shipping_address']['lastname'],
                                    'prefix' => '',
                                    'suffix' => '',
                                    'street' => $finalArray[$order->getEntityId()]['shipping_address']['street'],
                                    'city' => $finalArray[$order->getEntityId()]['shipping_address']['city'],
                                    'country_id' => $finalArray[$order->getEntityId()]['shipping_address']['country_id'],
                                    'region' => $finalArray[$order->getEntityId()]['shipping_address']['region'],
                                    'region_id' => $finalArray[$order->getEntityId()]['shipping_address']['region_id'],
                                    'postcode' => $finalArray[$order->getEntityId()]['shipping_address']['postcode'],
                                    'telephone' => $finalArray[$order->getEntityId()]['shipping_address']['telephone'],
                                    'fax' => $finalArray[$order->getEntityId()]['shipping_address']['fax'],
                                    'save_in_address_book' => 0
                                ],
                                'items' => $finalArray[$order->getEntityId()]['items']];
                            $store = $this->storeManager->getStore();
                            $storeId = $store->getStoreId();
                            $websiteId = $this->storeManager->getStore()->getWebsiteId();
                            $customer = $this->customerFactory->create()
                                ->setWebsiteId($websiteId)
                                ->loadByEmail($orderInfo['email']); // Customer email address
                            if (!$customer->getId()) {
                                /**
                                 * If Guest customer, Create new customer
                                 */
                                $customer->setStore($store)
                                    ->setFirstname($orderInfo['address']['firstname'])
                                    ->setLastname($orderInfo['address']['lastname'])
                                    ->setEmail($orderInfo['email'])
                                    ->setPassword('admin@123');
                                $customer->save();
                            }
                            $quote = $this->quote->create(); //Quote Object
                            $quote->setStore($store); //set store for our quote

                            /**
                             * Registered Customer
                             */
                            $customer = $this->customerRepository->getById($customer->getId());
                            $quote->setCurrency();
                            $quote->assignCustomer($customer); //Assign Quote to Customer

                            //Add Items in Quote Object
                            foreach ($orderInfo['items'] as $item) {
                                $product = $this->productRepository->getById($item['product_id']);
                                if (!empty($item['super_attribute'])) {
                                    /**
                                     * Configurable Product
                                     */
                                    $buyRequest = new \Magento\Framework\DataObject($item);
                                    $quote->addProduct($product, $buyRequest);
                                } else {
                                    /**
                                     * Simple Product
                                     */
                                    $quote->addProduct($product, intval($item['qty']));
                                }
                            }

                            //Billing & Shipping Address to Quote
                            $quote->getBillingAddress()->addData($orderInfo['address']);
                            $quote->getShippingAddress()->addData($orderInfo['address']);

                            // Set Shipping Method
                            $shippingAddress = $quote->getShippingAddress();
                            $shippingAddress->setCollectShippingRates(true)
                                ->collectShippingRates()
                                ->setShippingMethod('flatrate_flatrate');
                            //shipping method code, Make sure free shipping method is enabled
                            $quote->setPaymentMethod('cashondelivery');
                            //Payment Method Code, Make sure checkmo payment method is enabled
                            $quote->setInventoryProcessed(false);
                            $quote->save();
                            $quote->getPayment()->importData(['method' => 'cashondelivery']);

                            // Collect Quote Totals & Save
                            $quote->collectTotals()->save();
                            // Create Order From Quote Object
                            $order = $this->quoteManagement->submit($quote);
                            // Send Order Email to Customer Email ID
                            $this->orderSender->send($order);
                            // Get Order Increment ID
                            $orderId = $order->getIncrementId();
                            if ($orderId) {
                                // $result['success'] = $orderId;
                                $logger->info('Order Placed ' . $orderId);
                            } else {
                                $logger->info("Error occurs for Order placed");
                            }
                        }
                    }
                }
            }
        }
    }
}
