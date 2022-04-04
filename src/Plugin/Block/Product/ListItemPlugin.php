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
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    public function afterGetProductPriceHtml(ProductListItem $subject, $result, \Magento\Catalog\Model\Product $product)
    {
        $block = $this->layout->getBlock('amasty.rewards.highlight.category');

        if (!$block) {
            return $result;
        }

        $block->setProduct($product);

        $result .= $block->toHtml();

        return $result;
    }
}
