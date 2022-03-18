<?php

namespace Hyva\AmastyRewards\ViewModel\Catalog;

use Amasty\Rewards\Block\Frontend\Catalog\HighlightProduct as AmastyHighlightProduct;
use Amasty\Rewards\Api\GuestHighlightManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class HighlightProduct extends AmastyHighlightProduct implements ArgumentInterface
{
    /**
     * @var GuestHighlightManagementInterface
     */
    private $guestHighlightManagement;

    /**
     * @var CustomerSessionFactory
     */
    private $sessionFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    public function __construct(
        Template\Context $context,
        CustomerSessionFactory $sessionFactory,
        GuestHighlightManagementInterface $guestHighlightManagement,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $sessionFactory, $guestHighlightManagement, $data);

        $this->guestHighlightManagement = $guestHighlightManagement;
        $this->sessionFactory = $sessionFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * @return bool
     */
    protected function isLoggedIn()
    {
        return $this->sessionFactory->create()->isLoggedIn();
    }

    public function getCustomerId()
    {
        return $this->isLoggedIn() ? $this->customerSession->getId() : 0;
    }

    public function getJsConfig()
    {
        $config = [];

        if ($this->isLoggedIn()) {
            $config = [
                'productId' => $this->getProductId(),
                'refreshUrl' => str_replace('rewards', 'hyva-amasty-rewards', $this->getRefreshUrl()),
                'guest' => false
           ];
        } elseif ($this->guestHighlightManagement->isVisible(GuestHighlightManagementInterface::PAGE_PRODUCT)) {
            $config = $this->guestHighlightManagement
                    ->getHighlight(GuestHighlightManagementInterface::PAGE_PRODUCT)
                    ->getData();

            $config['guest'] = true;
        }

        return json_encode($config);
    }
}
