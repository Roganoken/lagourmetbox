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
class WIC_Mondialrelay_System_ConfigController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Enter description here...
     *
     */
    protected function _construct()
    {
        $this->setFlag('index', 'no-preDispatch', true);
        return parent::_construct();
    }

    public function exportAction()
    {        
        $this->exportCSV();
    }

    public function exportcdAction()
    {        
        $this->exportCSV('mondialrelay/carrier_pointsrelaiscd');
    }

    public function exportld1Action()
    {        
        $this->exportCSV('mondialrelay/carrier_pointsrelaisld1');
    }

    public function exportldsAction()
    {        
        $this->exportCSV('mondialrelay/carrier_pointsrelaislds');
    }
    
    public function exportCSV($class = 'mondialrelay/carrier_pointsrelais')
    {
        $websiteModel = Mage::app()->getWebsite($this->getRequest()->getParam('website'));

        $conditionName = $this->getRequest()->getParam('conditionName');
        
        Mage::log('conditionName : '.$conditionName);
        $tableratesCollection = Mage::getResourceModel($class.'_collection');
        $tableratesCollection->setConditionFilter($conditionName);
        $tableratesCollection->setWebsiteFilter($websiteModel->getId());
        $tableratesCollection->load();

        $csv = '';

        $conditionName = Mage::getModel($class)->getCode('condition_name_short', $conditionName);

        $csvHeader = array(
        	'"'.Mage::helper('adminhtml')->__('Country').'"', 
        	'"'.Mage::helper('adminhtml')->__('Region/State').'"', 
        	'"'.Mage::helper('adminhtml')->__('Zip/Postal Code').'"', 
        	'"'.$conditionName.'"', 
        	'"'.Mage::helper('adminhtml')->__('Shipping Price').'"'
        	);
        	
        $csv .= implode(',', $csvHeader)."\n";

        foreach ($tableratesCollection->getItems() as $item) 
        {
            if ($item->getData('dest_country') == '') 
            {
                $country = '*';
            } 
            else 
            {
                $country = $item->getData('dest_country');
            }
            
            if ($item->getData('dest_region') == '') 
            {
                $region = '*';
            } 
            else 
            {
                $region = $item->getData('dest_region');
            }
            
            if ($item->getData('dest_zip') == '') 
            {
                $zip = '*';
            } 
            else 
            {
                $zip = $item->getData('dest_zip');
            }

            $csvData = array(
            	$country, 
            	$region, 
            	$zip, 
            	$item->getData('condition_value'), 
            	$item->getData('price'),
            	);
            	
            foreach ($csvData as $cell) 
            {
                $cell = '"'.str_replace('"', '""', $cell).'"';
            }
            
            $csv .= implode(',', $csvData)."\n";
        }

        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=pointsrelais.csv");
        echo $csv;
        exit;
    }
}      