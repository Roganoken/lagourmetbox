<?php
/**
 * Call actions after configuration is saved
 */
class Smartwave_Legenda_Model_Observer
{
    // After any system config is saved
    public function legenda_controllerActionPostdispatchAdminhtmlSystemConfigSave()
    {
        return $this;
    }
    
    // After store view is saved
    public function legenda_storeEdit(Varien_Event_Observer $observer)
    {
        return $this;
    }
}
