<?php

namespace Lucasart\StockStatusLabel\Helper;

use Custom\SubscriptionFrequency\Model\SubscriptionFrequencyFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Created by Carl Owens (carl@partfire.co.uk)
 * Company: PartFire Ltd (www.partfire.co.uk)
 **/
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;
    private $subscriptionFrequencyFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        SubscriptionFrequencyFactory $subscriptionFrequencyFactory
    ) {
        parent::__construct($context);
        $this->httpContext = $httpContext;
        $this->subscriptionFrequencyFactory = $subscriptionFrequencyFactory;
    }

    public function isLoggedIn()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return $isLoggedIn;
    }

    public function getSubscriptionFrequencyHtml()
    {
        $schoolCollection = $this->subscriptionFrequencyFactory->create()->getCollection();
        $subscriptionsHtml = "";

        $subscriptionsHtml .= "<option value=''>Select Frequency</option>";
        foreach ($schoolCollection as $value) {
            $subscriptionsHtml .= "<option value='" . $value->getSubscriptionfrequencyId() . "'>"
                . $value->getFrequencyName() . "</option>";
        }
        return $subscriptionsHtml;
    }
}
