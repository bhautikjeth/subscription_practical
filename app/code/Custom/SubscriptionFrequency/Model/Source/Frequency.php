<?php
/**
 * @category   Custom
 * @package    Custom_SubscriptionFrequency
 * @author     thebhautik@gmail.com

 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Custom\SubscriptionFrequency\Model\Source;

use Custom\SubscriptionFrequency\Model\SubscriptionFrequencyFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Item status functionality model
 */
class Frequency extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**#@+
     * Item Status values
     */
    const DAILY = 0;
    const WEEKLY = 1;
    const MONTHLY = 2;
    const YEARLY = 3;

    const STATUS_DISABLED = 0;

    /**#@-*/

    protected $subscriptionFrequencyFactory;

    public function __construct(SubscriptionFrequencyFactory $subscriptionFrequencyFactory)
    {
        $this->subscriptionFrequencyFactory = $subscriptionFrequencyFactory;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    // phpcs:ignore
    public static function getOptionArray()
    {
        return [
            self::DAILY => __('Daily'),
            self::WEEKLY => __('Weekly'),
            self::MONTHLY => __('Monthly'),
            self::YEARLY => __('Yearly')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];
        $subscriptionFactoryCollection = $this->subscriptionFrequencyFactory->create()->getCollection();

        foreach ($subscriptionFactoryCollection as $value) {
            $result[] = ['value' => $value->getSubscriptionfrequencyId(), 'label' => $value->getFrequencyName()];
        }
        return $result;
    }
}
