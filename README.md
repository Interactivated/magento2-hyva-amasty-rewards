
# magento2-amasty-rewards
Hyvä Themes Compatibility module for Amasty_Rewards
 
## Installation
  
1. Install via composer
    ```
    composer config repositories.hyva-themes/magento2-amasty-rewards git git@gitlab.hyva.io:hyva-themes/hyva-compat/magento2-amasty-rewards.git
    composer require hyva-themes/magento2-amasty-rewards
    ```
2. Enable module
    ```
    bin/magento setup:upgrade
    ```

## Add link to view Reward Points to customer account

We have the original template
```
vendor/hyva-themes/magento2-default-theme/Magento_Customer/templates/header/customer-menu.phtml
```

For example, after the Orders link we will add a new one:

```
<a class="block px-4 py-2 lg:px-5 lg:py-2 hover:bg-gray-100"
               href="<?= $escaper->escapeUrl($block->getUrl('sales/order/history')) ?>"
            >
                <?= $escaper->escapeHtml(__('My Orders')); ?>
            </a>
...
```


