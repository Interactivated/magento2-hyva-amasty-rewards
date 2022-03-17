<?php

namespace Hyva\AmastyRewards\Plugin\Block\Product;
use Hyva\Theme\ViewModel\ProductListItem;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Customer\Model\Context as CustomerContext;

class ListItemPlugin
{

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product = null;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    public function beforeGetProductPriceHtml(
        ProductListItem $subject,
        \Magento\Catalog\Model\Product $product
    ) {
        $this->product = $product;

        return [$product];
    }

    public function afterGetProductPriceHtml(ProductListItem $subject, $result)
    {
        $block = $this->layout->getBlock('amasty.rewards.highlight.category');

        if (!$block) {
            return $result;
        }

        $block->setProduct($this->product);

        $result .= $block->toHtml();

        return $result;
    }
}
