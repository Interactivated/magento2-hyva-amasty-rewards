<?php

namespace Hyva\AmastyRewards\ViewModel\Catalog;

use Amasty\Rewards\Block\Frontend\Catalog\HighlightCategory as AmastyHighlightCategory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Amasty\Rewards\Api\GuestHighlightManagementInterface;
use Amasty\Rewards\Model\Config;

class HighlightCategory extends AmastyHighlightCategory implements ArgumentInterface
{
    public function __construct(
        Template\Context $context,
        CustomerSessionFactory $sessionFactory,
        GuestHighlightManagementInterface $guestHighlightManagement,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $sessionFactory, $guestHighlightManagement, $config, $data);
    }

    public function getJsConfig()
    {
        return json_encode([
            'productId' => $this->getProductId(),
            'refreshUrl' => $this->getRefreshUrl(),
            'formSelector' => '[data-product-sku="' . $this->getProductSku() . '"]'
        ]);
    }
}
