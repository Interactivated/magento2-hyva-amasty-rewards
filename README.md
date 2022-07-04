
# Hyvä Themes Compatibility module for Amasty_Rewards

## Compatibility features

1. Displaying a potencial rewards on category pages
<img width="1577" alt="" src="https://user-images.githubusercontent.com/414067/177096692-9cffb5a6-5443-4a17-874b-26d3f77aca34.png">

2. Showing logged-in customers their reward points balance
<img width="1048" alt="" src="https://user-images.githubusercontent.com/414067/177097886-23159cfa-6ed8-419b-b30e-d2ce9c5856b3.png">

3. Displaying a potencial earn points after buy product
<img width="1563" alt="" src="https://user-images.githubusercontent.com/414067/177098197-972ba907-ae91-4320-bf1a-5600141b22ac.png">

4. Showing a potencial earn points on the cart page
<img width="1581" alt="" src="https://user-images.githubusercontent.com/414067/177098840-582833cc-3267-4ff0-81a7-58c3a7c68b8d.png">

5. Redeem their reward points partially. Apply/Cancel controls.
<img width="1575" alt="" src="https://user-images.githubusercontent.com/414067/177099582-02e06d8f-78cd-476d-b3cb-37a60b4abe14.png">

<img width="1584" alt="" src="https://user-images.githubusercontent.com/414067/177099598-ca2cdd48-83b0-49a1-b996-7bb4bde002eb.png">

 
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

