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
class WIC_Mondialrelay_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$this->getAddressAction();
	
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function getAddressAction(){
		
		$addressId = $this->getRequest()->getParam('address', false);		
		// $addressId = 3;
		if($addressId) {
			$address = Mage::getModel('customer/address')->load((int)$addressId);
			$address->explodeStreetAddress();
			if ($address->getRegionId()) {
				$address->setRegion($address->getRegionId());
			}		
			header('Content-type: application/json');
			die(json_encode($address->getData()));
		}
	}
}