<?php
 
class Mage_Adminhtml_Model_System_Config_Source_Monedaoptions
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('EURO')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('DOLAR')),
        );
    }

}
?>