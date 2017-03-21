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

class WIC_Mondialrelay_Model_Observer
{
	public function getConfigData($field)
	{
        $path = 'carriers/pointsrelais/'.$field;
        return Mage::getStoreConfig($path, Mage::app()->getStore());
	}
    
    public function changeAddress()
    {

        $address = Mage::helper('checkout/cart')->getQuote()->getShippingAddress();
        $_shippingMethod = explode("_",$address->getShippingMethod());

        if ($_shippingMethod[0] == 'pointsrelais')
        {
            //On récupère l'identifiant du point relais
            $Num = $_shippingMethod[1];
            
            // On met en place les paramètres de la requète
            $params = array(
				'Enseigne'  => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
				'Num'       => $Num,
                'Pays'      => $address->getCountryId()
            );            
             //On crée le code de sécurité
			$code = implode("",$params);
			$code .= Mage::getStoreConfig('shipping/mondialrelay/cle');
			
			//On le rajoute aux paramètres
			$params["Security"] = strtoupper(md5($code));
			
            // On se connecte
            $soapClient = Mage::getSingleton('mondialrelay/soap');
                                    
            // Et on effectue la requète
            $detail_pointrelais = $soapClient->WSI2_DetailPointRelais($params)->WSI2_DetailPointRelaisResult;
            
            $address->setCompany($detail_pointrelais->LgAdr1)
                    ->setStreet(strtolower($detail_pointrelais->LgAdr2) . strtolower($detail_pointrelais->LgAdr3) . strtolower($detail_pointrelais->LgAdr4) )
                    ->setPostcode($detail_pointrelais->CP)
                    ->setCity($detail_pointrelais->Ville);
                    
            Mage::helper('checkout/cart')->getQuote()->setShippingAddress($address);

        }
    }
}