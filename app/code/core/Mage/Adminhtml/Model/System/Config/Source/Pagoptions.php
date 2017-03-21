<?php
 
class Mage_Adminhtml_Model_System_Config_Source_Pagoptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Todos')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Solo tarjeta')),
			array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Tarjeta y Iupay')),
        );
    }

	public function toArray()
	{
		return array(
			0 => Mage::helper('adminhtml')->__('Todos'),
			1 => Mage::helper('adminhtml')->__('Solo tarjeta'),
			2 => Mage::helper('adminhtml')->__('Tarjeta y Iupay'),
		);
	}
}
?>