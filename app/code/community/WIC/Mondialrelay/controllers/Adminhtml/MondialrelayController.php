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
 * Admin controller
 */
class WIC_Mondialrelay_Adminhtml_MondialrelayController extends Mage_Adminhtml_Controller_Action {
	
	//////////////
	////////////// System Config
	//////////////
	
	/**
	 * Test general configuration
	 */
	public function testAction() {		
		
		$soapClient = Mage::getSingleton('mondialrelay/soap');
		
		try {
		
		$result = $soapClient->testWsConnection();
		
		if (($errorNumber = $result->STAT) != 0)
		{		
			throw new Exception(Mage::helper('mondialrelay')->convertStatToTxt($errorNumber));
		}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mondialrelay')->__('Setup has been successfully validated.'));
		}
		catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mondialrelay')->__('Setup has failed. Mondial Relay WebService sent the following message: "%s".', $e->getMessage()));
		}
		
		$this->_redirect('*/system_config/edit/section/shipping');
	}

}