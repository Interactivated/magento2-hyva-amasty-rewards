<?php

namespace Hyva\AmastyRewards\Plugin\Block\Product;
use Hyva\Theme\ViewModel\ProductListItem;

class ListItemPlugin
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product = null;

    protected $layout;

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
        $block = $this->layout->getBlock('amasty.rewards.highlight.category')->setProduct($this->product);

        if (!$block) {
            return $result;
        }

        // $block->setProductId($this->product->getId())->setProductSku($this->product->getSku());
        $result .= $block->toHtml();

        return $result;
    }
}
