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
abstract class WIC_Mondialrelay_Model_Carrier_Abstract extends Mage_Shipping_Model_Carrier_Abstract 
{
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		try{

			$result = Mage::getModel('shipping/rate_result');
			if (!$this->getConfigData('active')) {
				return $result;
			}
			
			$shipping_free_cart_price = null;
			if ($this->getConfigData('free_active')) {
					$shipping_free_cart_price = $this->getConfigData('free_price');
			}

			$request->setConditionName($this->getConfigData('condition_name') ? $this->getConfigData('condition_name') : $this->_default_condition_name);
			
			if($this->getConfigData('package_weight')){
				$request->_data['package_weight'] = $request->_data['package_weight']+($this->getConfigData('package_weight')/1000);
			}
			$rates = $this->getRate($request);
			$cartTmp = $request->_data['package_value_with_discount'];
			$weghtTmp = $request->_data['package_weight'];
			foreach($rates as $rate)
			{
				if (!empty($rate) && $rate['price'] >= 0) 
				{

	/*---------------------------------------- Liste des points relais -----------------------------------------*/
					$params = array(
								'Enseigne'     => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
								   'Pays'         => $request->_data['dest_country_id'],
								   'CP'           => $request->_data['dest_postcode'],
								   'Action'       => $this->_action,
					);
					
					$code = implode("",$params);
					$code .= Mage::getStoreConfig('shipping/mondialrelay/cle');				
					
					$params["Security"] = strtoupper(md5($code));					

					$client = Mage::getSingleton('mondialrelay/soap');
					
					$points_relais = $client->WSI2_RecherchePointRelais($params)->WSI2_RecherchePointRelaisResult;
					
					foreach( $points_relais as $point_relais ) {
						if ( is_object($point_relais) && trim($point_relais->Num) != '' ) {
							
							$method = Mage::getModel('shipping/rate_result_method');

							$method->setCarrier($this->_code);
							$method->setCarrierTitle($this->getConfigData('title'));
							// Clean gmaps...
							$address = trim($point_relais->LgAdr3).", ".trim($point_relais->CP).", ".trim($point_relais->Ville);
							
							// $methodTitle = $point_relais->LgAdr1 . ' - ' . $point_relais->Ville  . ' <a href="#" onclick="PointsRelais.showInfo(\'' . $point_relais->Num . '\'); return false;">Détails</a> - <span style="display:none;" id="pays">' . $request->_data['dest_country_id'] . '</span>';
							$methodTitle = '<div class="prdiv">' . trim($point_relais->LgAdr1) . '<span class="prdivln1">' . trim($point_relais->LgAdr3) . '</BR>' . trim($point_relais->CP)  . ' - ' . trim($point_relais->Ville) . '</BR>' . '<a href="https://www.google.com/maps/search/' . $address  . '" target="_blankw">Map</a>'  . '</span></div>';
							
// >Lgdr1 // Nom du Point Relais® (Ligne 1) 
// >LgAdr2 // Nom du Point Relais® (Ligne 2) 
// >LgAdr3 // Adresse du Point Relais® (Ligne 3) 
// >LgAdr4 // Adresse du Point Relais® (Ligne 4) 
// >CP // Code postal du Point Relais®// >Ville //  Ville du Point Relais®// >Pays // Code Pays ISO du Point Relais®// >Latitude// >Longitude// >TypeActivite// >Distance// >Localisation1// >Localisation2// >Horaire_ Lundi// >Horaire_ Mardi// >Horaire_ Mercredi// >Horaire_ Jeudi// >Horaire_ Vendredi// >Horaire_ Samedi// >Horaire_ Dimanche// >URL_Plan// >URL_Photo			
							
							$method->setMethod($point_relais->Num);
							$method->setMethodTitle($methodTitle);
			
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
				}            
			}

			return $result;
		}
		catch(exception $e)
		{
			return 0;
		}
	}
	
	public function getRate(Mage_Shipping_Model_Rate_Request $request)
	{	
		$ressource = Mage::getResourceModel('mondialrelay/carrier_'.$this->_code);
		$rate = $ressource->getRate($request);
		return $rate;
	}	
	
	public function getCode($type, $code='')
    {
        $codes = array(

            'condition_name'=>array(
                'package_weight' => Mage::helper('shipping')->__('Weight vs. Destination'),
                'package_value'  => Mage::helper('shipping')->__('Price vs. Destination'),
                'package_qty'    => Mage::helper('shipping')->__('# of Items vs. Destination'),
            ),

            'condition_name_short'=>array(
                'package_weight' => Mage::helper('shipping')->__('Poids'),
                'package_value'  => Mage::helper('shipping')->__('Valeur du panier'),
                'package_qty'    => Mage::helper('shipping')->__('Nombre d\'articles'),
            ),

        );

        if (!isset($codes[$type])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Tablerate Rate code type: %s', $type));
        }

        if (''===$code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Tablerate Rate code for type %s: %s', $type, $code));
        }

        return $codes[$type][$code];
    }

	public function isTrackingAvailable()
	{
		return true;
	}
	
	public function getTrackingInfo($tracking_number)
	{
		$tracking_result = $this->getTracking($tracking_number);

		if ($tracking_result instanceof Mage_Shipping_Model_Tracking_Result)
		{
			if ($trackings = $tracking_result->getAllTrackings())
			{
				return $trackings[0];
			}
		}
		elseif (is_string($tracking_result) && !empty($tracking_result))
		{
			return $tracking_result;
		}
		
		return false;
	}
	
	public function getAllowedMethods()
    {
        return array($this->_code => $this->getConfigData('name'));
    }
	
	protected function getTracking($tracking_number)
	{
		$key = '<' . $this->getConfigData('enseigne').$this->getConfigData('url') .'>' . $tracking_number . '<' . $this->getConfigData('cle') . '>';
		$key = md5($key);
		
		$tracking_url = 'http://www.mondialrelay.com/public/permanent/tracking.aspx?ens=ESGOUBOX&language=FR&nexp=' . strtoupper($tracking_number) . '&crc=' . strtoupper($key) ;

		$tracking_result = Mage::getModel('shipping/tracking_result');

		$tracking_status = Mage::getModel('shipping/tracking_result_status');
		$tracking_status->setCarrier($this->_code)
						->setCarrierTitle($this->getConfigData('title'))
						->setTracking($tracking_number)
						->setUrl($tracking_url);
		$tracking_result->append($tracking_status);

		return $tracking_result;
	}
}