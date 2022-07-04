
# Hyvä Themes Compatibility module for Amasty_Rewards

## Compatibility features

1. Displaying a potencial rewards on category pages
<img width="1577" alt="potencial rewards" src="https://user-images.githubusercontent.com/414067/177096692-9cffb5a6-5443-4a17-874b-26d3f77aca34.png">

2. Showing logged-in customers their reward points balance
<img width="1048" alt="balance" src="https://user-images.githubusercontent.com/414067/177097886-23159cfa-6ed8-419b-b30e-d2ce9c5856b3.png">

3. Displaying a potencial earn points after buy product
<img width="1563" alt="" src="https://user-images.githubusercontent.com/414067/177098197-972ba907-ae91-4320-bf1a-5600141b22ac.png">

4. 

 
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
   
## Workaround for fix the FPC issue on category pages for auth customer:

1. Copy the `vendor/hyva-themes/magento2-default-theme/Magento_Catalog/templates/product/list.phtml` to you own theme
2. Change line `->setData('cache_lifetime', 3600)` to `->setData('cache_lifetime', 0)`.

