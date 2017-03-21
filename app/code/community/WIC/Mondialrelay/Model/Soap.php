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
/**
 * Soap object
 *
 */
class WIC_Mondialrelay_Model_Soap extends Zend_Soap_Client {
		
	protected $_params = array();
	protected $_server_api = "http://www.mondialrelay.fr/webservice/Web_Services.asmx?WSDL";
	
	/**
	 * Constructor that defines WSDL and automatically connects to WS on model instanciation
	 */
	public function __construct() {
		parent::__construct($this->_server_api);
		$this->_connect();
	}
	
	/**
	 * Connect to Mondial Relay WS
	 * 
	 * @return WIC_Mondialrelay_Model_Soap
	 */
	protected function _connect() 
	{
	
		$this->_params = $this->getParams();	
		
	}
	
	
	private function getParams($params = Array())
	{
		$_params['Enseigne'] 	= Mage::getStoreConfig('shipping/mondialrelay/enseigne');
		
		if (!empty($params))
		{
			
			array_push($_params,$params);
		} 
		else
		{
		
			$_params['Pays'] 	= Mage::getStoreConfig('shipping/origin/country_id');
			$_params['Ville'] 	= Mage::getStoreConfig('shipping/origin/city');
			$_params['CP'] 		= Mage::getStoreConfig('shipping/origin/postcode');
			$_params['Poids'] 	= '';
			$_params['Taille'] 	= '';
			$_params['Action'] 	= '';			
		
		}		


		$_params['Security']	= $this->getSecurity($_params);
		
		return $_params;
		
	}
	
	private function getSecurity($params)
	{
		
		$code  = implode("",$params);		
		$code .= Mage::getStoreConfig('shipping/mondialrelay/cle');
		
		return strtoupper(md5($code));
	}
	
	/**
	 * Test connection
	 * 
	 * @return bool || Exception
	 */
	public function testWsConnection() 
	{

		return $this->WSI2_RecherchePointRelais($this->_params)->WSI2_RecherchePointRelaisResult;
	}
	
	/**
	 * Test connection
	 * 
	 * @return bool || Exception
	 */
	public function DetailPointRelais($params) 
	{

		
		return $this->WSI2_DetailPointRelais($this->getParams($params))->WSI2_DetailPointRelaisResult;
	}		
		
	
}