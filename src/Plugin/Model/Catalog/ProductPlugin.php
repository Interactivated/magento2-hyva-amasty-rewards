<?php

namespace Hyva\AmastyRewards\Plugin\Model\Catalog;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Customer\Model\Context as CustomerContext;

class ProductPlugin
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    public function __construct(
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->httpContext = $httpContext;
    }

    public function afterGetIdentities(AbstractProduct $subject, array $result)
    {
        $result[] = 'logged_' . $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);

        return $result;
    }

}
