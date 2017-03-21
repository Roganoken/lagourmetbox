<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2013 Mondial Relay
 * @author : Web in Color (http://www.webincolor.fr)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class WIC_Mondialrelay_Model_Carrier_Pointsrelaisld1 extends WIC_Mondialrelay_Model_Carrier_Abstract
{
	protected $_code = 'pointsrelaisld1';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
        $result = Mage::getModel('shipping/rate_result');
        if (!$this->getConfigData('active')) {
            return $result;
        }
        
        $shipping_free_cart_price = null;
        if ($this->getConfigData('free_active')) {
            	$shipping_free_cart_price = $this->getConfigData('free_price');
        }
		

        $request->setConditionName($this->getConfigData('condition_name') ? $this->getConfigData('condition_name') : $this->_default_condition_name);


        $result = Mage::getModel('shipping/rate_result');
        
        $rates = $this->getRate($request);
		$cartTmp = $request->_data['package_value_with_discount'];
		$weghtTmp = $request->_data['package_weight'];
        
        foreach($rates as $rate)
        {
            if (!empty($rate) && $rate['price'] >= 0) 
            {
				$method = Mage::getModel('shipping/rate_result_method');
				$method->setCarrier('pointsrelaisld1');
				$method->setCarrierTitle($this->getConfigData('title'));
				$method->setMethod('pointsrelaisld1');
				$method->setMethodTitle($this->getConfigData('description'));
				if($shipping_free_cart_price != null && ($cartTmp > $shipping_free_cart_price && $weghtTmp > 0.101)){
							$price = $rate['price'] = 0;
							$rate['cost']  = 0;
							$method->setPrice($price);
							$method->setCost($rate['cost']);
				}else{
					   		$price = $rate['price'];
						   	$method->setPrice($this->getFinalPriceWithHandlingFee($price));
							$method->setCost($rate['cost']);
				}
				$result->append($method);
            }            
        }

        return $result;
	}
	
	

}
