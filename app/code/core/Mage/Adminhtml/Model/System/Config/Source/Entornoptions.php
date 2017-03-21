<?php
 
class Mage_Adminhtml_Model_System_Config_Source_Entornoptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Real')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Sis-d')),
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Sis-i')),
			array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('Sis-t')),
        );
    }

	public function toArray()
	{
		return array(
			0 => Mage::helper('adminhtml')->__('Real'),
			1 => Mage::helper('adminhtml')->__('Sis-d'),
			2 => Mage::helper('adminhtml')->__('Sis-i'),
			3 => Mage::helper('adminhtml')->__('Sis-t'),
		);
	}
}
?>