
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

1. Add to the `layout/default.xml` handle this block to the body section:

```
        <referenceBlock name="header.customer">
            <block class="Amasty\Rewards\Block\Frontend\Balance" name="rewards.logged.balance" template="Hyva_AmastyRewards::balance.phtml"/>
        </referenceBlock>
```


2. Copy the template `vendor/hyva-themes/magento2-default-theme/Magento_Customer/templates/header/customer-menu.phtml` to our custom theme. For example, after the Orders link we will add a new one:

```
<a class="block px-4 py-2 lg:px-5 lg:py-2 hover:bg-gray-100"
               href="<?= $escaper->escapeUrl($block->getUrl('sales/order/history')) ?>"
            >
                <?= $escaper->escapeHtml(__('My Orders')); ?>
            </a>
...

<!-- Display Rewards balance -->
<?= $block->getChildHtml('rewards.logged.balance') ?>

```

Also, based on your preferences, you can add to any other place.
