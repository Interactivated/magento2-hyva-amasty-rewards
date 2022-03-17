<?php

namespace Hyva\AmastyRewards\ViewModel\Catalog;

use Amasty\Rewards\Block\Frontend\Catalog\HighlightCategory as AmastyHighlightCategory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Amasty\Rewards\Api\GuestHighlightManagementInterface;
use Amasty\Rewards\Model\Config;

class HighlightCategory extends AmastyHighlightCategory implements ArgumentInterface
{
    /**
     * @var Data
     */
    protected $urlHelper;

    /**
     * @var AbstractProduct
     */
    protected $abstractProduct;

    public function __construct(
        Template\Context $context,
        CustomerSessionFactory $sessionFactory,
        GuestHighlightManagementInterface $guestHighlightManagement,
        Config $config,
        Data $urlHelper,
        AbstractProduct $abstractProduct,
        array $data = []
    ) {
        parent::__construct($context, $sessionFactory, $guestHighlightManagement, $config, $data);
        $this->urlHelper = $urlHelper;
        $this->abstractProduct = $abstractProduct;
    }

    public function getJsConfig(Product $product)
    {
        $url = $this->abstractProduct->getAddToCartUrl($product, ['_escape' => false]);
        return json_encode([
            'refreshUrl' => $this->getRefreshUrl(),
            'productId' => $product->getId(),
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ]);
    }
}
