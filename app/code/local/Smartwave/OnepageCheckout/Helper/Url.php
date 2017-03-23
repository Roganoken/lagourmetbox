<?php
class Smartwave_OnepageCheckout_Helper_Url extends Mage_Checkout_Helper_Url
{
    public function getCheckoutUrl()
    {
        if (Mage::helper('onepagecheckout')->isOnepageCheckoutEnabled()) {
            $url = $this->_getUrl('onepagecheckout', array('_secure'=>true));
        } else {
            $url = $this->_getUrl('checkout/onepage', array('_secure'=>true));
        }
        
        return $url;
    }
}
