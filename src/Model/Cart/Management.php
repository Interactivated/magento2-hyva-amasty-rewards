<?php

namespace Hyva\AmastyRewards\Model\Cart;

use Amasty\Rewards\Api\CheckoutRewardsManagementInterface;
use Hyva\AmastyRewards\Api\AmountManagementInterface;
use Magento\Checkout\Model\Session;
use Psr\Log\LoggerInterface;

class Management implements AmountManagementInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CheckoutRewardsManagementInterface
     */
    private $rewardsManagement;

    /**
     * @var Session
     */
    private $checkoutSession;

    public function __construct(
        Session $checkoutSession,
        CheckoutRewardsManagementInterface $rewardsManagement,
        LoggerInterface $logger
    ) {
        $this->rewardsManagement = $rewardsManagement;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function usePoints($customerId, $amount = 0, $remove = 0): string
    {
        $applyCode = $remove == 1 ? 0 : 1;
        $cartQuote = $this->checkoutSession->getQuote();

        try {
            if ($applyCode) {
                $pointsData = $this->rewardsManagement->set($cartQuote->getId(), $amount);

                return __($pointsData['notice']);
            } else {
                $itemsCount = $cartQuote->getItemsCount();

                if ($itemsCount) {
                    $this->rewardsManagement->collectCurrentTotals($cartQuote, 0);
                }

                return __('You Canceled Reward');
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return __('We cannot Reward.');
        }
    }


}
