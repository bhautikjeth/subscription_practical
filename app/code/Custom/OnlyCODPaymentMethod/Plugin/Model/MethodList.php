<?php

namespace Custom\OnlyCODPaymentMethod\Plugin\Model;

use Magento\Quote\Api\Data\CartInterface;

class MethodList
{
    public function afterGetAvailableMethods(
        \Magento\Payment\Model\MethodList $subject,
        $availableMethods,
        CartInterface $quote = null
    )
    {
        $shippingMethod = $this->getShippingMethodFromQuote($quote);
        foreach ($availableMethods as $key => $method) {
            // Here we will hide CashonDeliver method while customer select FlateRate Shipping Method
            if (($method->getCode() != 'cashondelivery') && ($shippingMethod == 'flatrate_flatrate')) {
                unset($availableMethods[$key]);
            }
        }

        return $availableMethods;
    }

    /**
     * @param CartInterface $quote
     * @return string
     */
    private function getShippingMethodFromQuote($quote)
    {
        if ($quote) {
            return $quote->getShippingAddress()->getShippingMethod();
        }

        return '';
    }
}
