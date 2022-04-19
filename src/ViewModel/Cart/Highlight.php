<?php

namespace Hyva\AmastyRewards\ViewModel\Cart;

use Amasty\Rewards\Api\CheckoutHighlightManagementInterface;
use Amasty\Rewards\Block\Frontend\Cart\Highlight as AmastyHighlightCart;
use Amasty\Rewards\Api\GuestHighlightManagementInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Highlight extends AmastyHighlightCart implements ArgumentInterface
{
    /**
     * @var CheckoutHighlightManagementInterface
     */
    private $highlightManagement;

    /**
     * @var GuestHighlightManagementInterface
     */
    private $guestHighlightManagement;

    public function __construct(
        Template\Context $context,
        CheckoutHighlightManagementInterface $highlightManagement,
        GuestHighlightManagementInterface $guestHighlightManagement,
        array $data = []
    ) {
        parent::__construct($context, $highlightManagement, $guestHighlightManagement, $data);

        $this->highlightManagement = $highlightManagement;
        $this->guestHighlightManagement = $guestHighlightManagement;
    }

    public function getJsConfig()
    {
        $config = [];

        if ($this->highlightManagement->isVisible(CheckoutHighlightManagementInterface::CART)) {
            $config = $this->highlightManagement->getHighlightData();
            $config['highlight']['caption_end_text'] = __('for buying this product!');

        } elseif ($this->guestHighlightManagement->isVisible(GuestHighlightManagementInterface::PAGE_CART)) {
            $config = $this->guestHighlightManagement
                    ->getHighlight(GuestHighlightManagementInterface::PAGE_CART)
                    ->getData();
            $config['highlight']['caption_end_text'] = __('for registration!');
        }

        return $config;
    }
}
