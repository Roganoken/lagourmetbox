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
 * Test connection configuration button
 */
class WIC_Mondialrelay_Block_System_Config_Test extends Mage_Adminhtml_Block_System_Config_Form_Field 
{
	
	/**
	 * Generate html for button
	 * 
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string $html
	 * @see Mage_Adminhtml_Block_System_Config_Form_Field::_getElementHtml()
	 */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
		$this->setElement($element);
		
		$url = $this->getUrl('adminhtml/mondialrelay/test');
		
		$html = $this->getLayout()->createBlock('adminhtml/widget_button')
			->setType('button')
			->setLabel($this->__('Test connection setup'))
			->setOnClick("setLocation('$url')")
			->setId($element->getHtmlId())
			->toHtml();
		
		return $html;
	}
}