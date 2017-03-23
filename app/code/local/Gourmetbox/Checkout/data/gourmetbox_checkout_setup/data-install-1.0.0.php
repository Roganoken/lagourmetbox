<?php

/* Enable Magento default checkout */
Mage::getConfig()->saveConfig('checkout/options/onepage_checkout_enabled', '1', 'default', 0);
Mage::getConfig()->saveConfig('checkout/options/onepage_checkout_enabled', '1', 'websites', 1);

/* Keep cart for 30 days */
Mage::getConfig()->saveConfig('checkout/cart/delete_quote_after', '30', 'default', 0);
