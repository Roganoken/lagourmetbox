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
 
 class WIC_Mondialrelay_Block_Info extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	
	public $enseigne = 'test';
	
	public function getConfigData($field)
	{
        $path = 'carriers/pointsrelais/'.$field;
        return Mage::getStoreConfig($path, $this->getStore());
	}
	
	public function getDetailPointRelais()
	{		
		// On met en place les paramètres de la requète
		$params = array(
					   'Enseigne'  => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
					   'Num'       => $this->getRequest()->getPost('Id_Relais'),
					   'Pays'      => $this->getRequest()->getPost('Pays')
		);
		
		//On crée le code de sécurité
		$code = implode("",$params);
		$code .= $this->getConfigData('cle');
		
		//On le rajoute aux paramètres
		$params["Security"] = strtoupper(md5($code));
		
		// On se connecte
		$client = Mage::getSingleton('mondialrelay/soap');
		
		// Et on effectue la requète
		$detail_pointrelais = $client->WSI2_DetailPointRelais($params)->WSI2_DetailPointRelaisResult;
		return $detail_pointrelais;
	}
	
    
}