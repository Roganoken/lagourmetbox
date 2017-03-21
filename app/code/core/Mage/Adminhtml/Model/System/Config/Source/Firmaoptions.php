<?php
 
class Mage_Adminhtml_Model_System_Config_Source_Firmaoptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Ampliada')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Completa')),
        );
    }

	public function toArray()
	{
		return array(
			0 => Mage::helper('adminhtml')->__('Ampliada'),
			1 => Mage::helper('adminhtml')->__('Completa'),
		);
	}
}
?>