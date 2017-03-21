<?php
class WIC_Mondialrelay_Model_System_Config_Source_Format
{
	public function toOptionArray()
    {
        return array(
            array('value' => 'A4', 'label'=>Mage::helper('adminhtml')->__('A4')),
            array('value' => 'A5', 'label'=>Mage::helper('adminhtml')->__('A5')),
			array('value' => '10x15', 'label'=>Mage::helper('adminhtml')->__('10x15')),
        );
    }
}