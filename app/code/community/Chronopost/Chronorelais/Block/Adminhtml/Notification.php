<?php
class Chronopost_Chronorelais_Block_Adminhtml_Notification extends Mage_Core_Block_Template
{
    const XML_SEVERITY_ICONS_URL_PATH  = 'system/adminnotification/severity_icons_url';

    const MODULE_RELEASES_XML_URL = 'http://connect20.magentocommerce.com/community/Chronopost/releases.xml';
    //all community packages => http://connect20.magentocommerce.com/community/packages.xml

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('chronorelais/notification.phtml');
        }
        return $this;
    }

    public function getSeverityIconsUrl()
    {
        return (Mage::app()->getFrontController()->getRequest()->isSecure() ? 'https://' : 'http://')
                . sprintf(Mage::getStoreConfig(self::XML_SEVERITY_ICONS_URL_PATH), Mage::getVersion(),
                    'SEVERITY_NOTICE');
    }

    public function canShow()
    {
        if (!Mage::getSingleton('admin/session')->isFirstPageAfterLogin()) {
            return false;
        }
        return true;
    }

    public function getNotifications()
    {
        return $this;
    }

}
