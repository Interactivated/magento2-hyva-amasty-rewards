<?php
declare(strict_types=1);

namespace Hyva\AmastyRewards\Controller\Index;

use Amasty\Rewards\Api\CheckoutRewardsManagementInterface;
use Amasty\Rewards\Controller\Index\RewardPost as AmastyRewardPost;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;

class RewardPost extends AmastyRewardPost implements HttpPostActionInterface
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
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
        LoggerInterface $logger,
        CheckoutRewardsManagementInterface $rewardsManagement,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $logger,
            $rewardsManagement
        );
        $this->logger = $logger;
        $this->rewardsManagement = $rewardsManagement;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $applyCode = $this->getRequest()->getParam('remove') == 1 ? 0 : 1;
        $cartQuote = $this->_checkoutSession->getQuote();
        $usedPoints = $this->getRequest()->getParam('amreward_amount', 0);

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        try {
            if ($applyCode) {
                $pointsData = $this->rewardsManagement->set($cartQuote->getId(), $usedPoints);
                return $resultJson->setData([
                    'message' => __($pointsData['notice']),
                    'usedPoints' => $usedPoints
                ]);
            } else {
                $itemsCount = $cartQuote->getItemsCount();

                if ($itemsCount) {
                    $this->rewardsManagement->collectCurrentTotals($cartQuote, 0);
                }

                return $resultJson->setData([
                    'message' => __('You Canceled Reward'),
                    'usedPoints' => 0
                ]);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $resultJson->setData([
                'message' => __('We cannot Reward.')
            ]);
        }
    }
}
