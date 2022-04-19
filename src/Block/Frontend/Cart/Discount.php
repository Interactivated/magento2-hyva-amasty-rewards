<?php

namespace Hyva\AmastyRewards\Block\Frontend\Cart;

use Amasty\Rewards\Api\Data\SalesQuote\EntityInterface;
use Amasty\Rewards\Model\Config\Source\RedemptionLimitTypes;
use Amasty\Rewards\Model\RewardsPropertyProvider;
use Amasty\Rewards\Model\Calculation\Discount as Calculator;
use Amasty\Rewards\Model\Config;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote;

/**
 * Product View block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Discount extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Quote
     */
    private $quote;

    /**
     * @var Total
     */
    private $total;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @var RewardsPropertyProvider
     */
    private $rewardsPropertyProvider;

    /**
     * @var ShippingAssignmentInterface
     */
    private $shippingAssignment;

    /**
     * @var array
     */
    private $rewardsData;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    protected $checkoutSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        RewardsPropertyProvider $rewardsPropertyProvider,
        ShippingAssignmentInterface $shippingAssignment,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        Quote $quote,
        Total $total,
        Config $config,
        Calculator $calculator,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data
    ) {
        $this->rewardsPropertyProvider = $rewardsPropertyProvider;
        $this->shippingAssignment = $shippingAssignment;
        parent::__construct($context, $data);
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->quote = $quote;
        $this->total = $total;
        $this->config = $config;
        $this->calculator = $calculator;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return mixed
     */
    public function getUsedPoints()
    {
        return $this->priceCurrency->round($this->getRewardsData()['pointsUsed']);
    }

    /**
     * @return mixed
     */
    public function getApplyedDiscount()
    {
        $quote = $this->checkoutSession->getQuote();

        $storeId = $quote->getStoreId();

        $total = $this->checkoutSession->getQuote()->getTotals();
        $items = $this->checkoutSession->getQuote()->getItems();

        $spentPoints = (float)$quote->getData(EntityInterface::POINTS_SPENT);

        if (!$items) {
            return $this;
        }

        $appliedPoints = $this->calculator->calculateDiscount($items, $total['shipping'], $spentPoints);

        $isEnableLimit = $this->config->isEnableLimit($storeId);

        if ((int)$isEnableLimit === RedemptionLimitTypes::LIMIT_PERCENT) {
            $limitPercent = $this->config->getRewardPercentLimit($storeId);
            $rate = $this->config->getPointsRate($storeId);
            $basePoints = $appliedPoints / $rate;
            $allowedPercent = round(($total->getSubtotal() / 100 * $limitPercent) / $quote->getBaseToQuoteRate(), 2);

            if ($basePoints > $allowedPercent) {
                $itemsCount = $quote->getItemsCount();

                if ($itemsCount) {
                    $total->setDiscountAmount(0);
                    $total->setBaseDiscountAmount(0);
                }

                $quote->setData(EntityInterface::POINTS_SPENT, 0);
            }
        }

        return $this->getQuoteAppliedPoints($items);
    }

    /**
     * Retrieve customer data object
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getRewardsData()['customerId'];
    }

    /**
     * @return array
     */
    private function getRewardsData()
    {
        if (!isset($this->rewardsData)) {
            $this->rewardsData = $this->rewardsPropertyProvider->getRewardsData();
        }

        return $this->rewardsData;
    }

    /**
     * @param CartItemInterface[] $items
     * @return float
     */
    private function getQuoteAppliedPoints(array $items): float
    {
        $points = 0;

        foreach ($items as $item) {
            $points += $item->getData(EntityInterface::POINTS_SPENT);
        }
        $storeId = (int)$this->storeManager->getStore()->getId();
        $roundRule = $this->config->getRoundRule($storeId);
        if ($roundRule === 'up') {
            $points = ceil($points);
        }

        return (float)$points;
    }
}
