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

class WIC_Mondialrelay_Sales_ImpressionController extends Mage_Adminhtml_Controller_Action
{
    protected $_trackingNumbers = array();
    
    /**
     * Additional initialization
     *
     */
    protected function _construct()
    {
        $this->setUsedModuleName('WIC_Mondialrelay');
    }


    /**
     * Shipping grid
     */
    public function indexAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/mondialrelay')
			->_addContent($this->getLayout()->createBlock('mondialrelay/sales_impression'))
            ->renderLayout();
    }
    
	public function getConfigData($field)
	{
        $path = 'carriers/pointsrelais/'.$field;
        return Mage::getStoreConfig($path, Mage::app()->getStore());
	}
    
    protected function _processDownload($resource, $resourceType)
    {
        $helper = Mage::helper('downloadable/download');
        /* @var $helper Mage_Downloadable_Helper_Download */

        $helper->setResource($resource, $resourceType);

        $fileName       = $helper->getFilename();
        $contentType    = $helper->getContentType();

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
			->setHeader('Last-Modified', date('r'))
			->setHeader('Content-Disposition', 'attachment; filename='.$fileName)
		;			

        if ($fileSize = $helper->getFilesize()) {
            $this->getResponse()
                ->setHeader('Content-Length', $fileSize);
        }

        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        $helper->output();
		die;
    }
    
    protected function getTrackingNumber($shipmentId)
    {
                Mage::Log('***getTrackingNumber****');
Mage::Log('***getTrackingNumber**** 1 : '.$shipmentId);
        $shipment = Mage::getModel('sales/order_shipment')->load($shipmentId);
        $trackingNumbersToReturn = array();
        //On récupère le numéro de tracking
        $tracks = $shipment->getTracksCollection();
        //->addAttributeToFilter('carrier_code', array('like' => 'pointsrelais%'));
        
        foreach ($tracks as $track) {
Mage::Log('***getTrackingNumber**** 2 : '.$track->getnumber());

                $trackingNumbersToReturn[] = $track->getnumber();
 
        }
        
        return $trackingNumbersToReturn;
    }
    
    protected function getEtiquetteUrl($shipmentsIds)
    {
                Mage::Log('***getEtiquetteUrl****');
        //On récupère les infos d'expédition
        if (is_array($shipmentsIds))
        {
            foreach($shipmentsIds as $shipmentsId)
            {
                array_merge($this->_trackingNumbers, $this->getTrackingNumber($shipmentsId));
            }
            foreach($this->_trackingNumbers as $trackingId)
            {
            	
                Mage::Log('********');
                Mage::Log('$trackingId : ',$trackingId);
                Mage::Log('********');
            }
        }
        else
        {
            $shipmentId = $shipmentsIds;
            $this->_trackingNumbers = $this->getTrackingNumber($shipmentId);            
        };
        
        // On met en place les paramètres de la requète
        $params = array(
                       'Enseigne'       => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
                       'Expeditions'    => implode(';',$this->_trackingNumbers),
                       'Langue'    => 'FR',
        );
        //On crée le code de sécurité
        $code = implode("",$params);
        $code .= Mage::getStoreConfig('shipping/mondialrelay/cle');
        
        //On le rajoute aux paramètres
        $params["Security"] = strtoupper(md5($code));
        
        // On se connecte        
		$client = Mage::getSingleton('mondialrelay/soap');
        
        // Et on effectue la requète
        $etiquette = $client->WSI2_GetEtiquettes($params)->WSI2_GetEtiquettesResult;
        $printformat = Mage::getStoreConfig('shipping/mondialrelay/printformat');
		if($printformat == 'A5')
			return $etiquette->URL_PDF_A5;
		elseif($printformat == 'A4')
			return $etiquette->URL_PDF_A4;
		else 
			return str_replace("format=A4","format=".$printformat,$etiquette->URL_PDF_A4);
    }
    
    protected function getEtiquetteUrlFromTrack($trackIds)
    {
                Mage::Log('***getEtiquetteUrlFromTrack****');
			$mrTrackingNumber = array();
            foreach($trackIds as $trackingId)
            {
            	
                Mage::Log('********');
                Mage::Log('$trackingId : '.$trackingId);
                Mage::Log('********');
                $trackObj = Mage::getModel('sales/order_shipment_track')->load($trackingId);
                $mrTrackingNumber[] = $trackObj->getnumber();
            }
        
	        // On met en place les paramètres de la requète
	        $params = array(
	                       'Enseigne'       => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
	                       'Expeditions'    => implode(';',$mrTrackingNumber),
	                       'Langue'    => 'FR',
	        );
	        
            Mage::Log('$trackingIds : '.implode(';',$mrTrackingNumber));
            
	        //On crée le code de sécurité
	        $code = implode("",$params);
	        $code .= Mage::getStoreConfig('shipping/mondialrelay/cle');
	        
	        //On le rajoute aux paramètres
	        $params["Security"] = strtoupper(md5($code));
	        
	        // On se connecte
	        $client = new SoapClient("http://www.mondialrelay.com/WebService/Web_Services.asmx?WSDL");
	        // Et on effectue la requète
	        $etiquette = $client->WSI2_GetEtiquettes($params)->WSI2_GetEtiquettesResult;
	        
	        Mage::Log('********2');
                Mage::Log($etiquette);
                Mage::Log('********2');
	        return $etiquette;

    }
    
    public function printMassAction()
    {
        $trackIds = $this->getRequest()->getPost('track_ids');
       if(!is_array($trackIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Merci de sélectionner des commandes.'));

        } else {
	        try {
	            $etiquette = $this->getEtiquetteUrlFromTrack($trackIds);				
	            if($etiquette->STAT == 0){
					$printformat = Mage::getStoreConfig('shipping/mondialrelay/printformat');					
					if($printformat == 'A5') {
						$this->_processDownload('http://www.mondialrelay.fr' . $etiquette->URL_PDF_A5, 'url');
					}
					elseif($printformat == 'A4') {
						$this->_processDownload('http://www.mondialrelay.fr' . $etiquette->URL_PDF_A4, 'url');					
					}
					else {
						$this->_processDownload('http://www.mondialrelay.fr' .str_replace("format=A4","format=".$printformat,$etiquette->URL_PDF_A4), 'url');
					}
	            }else{
	            	 $this->_getSession()->addError(Mage::helper('mondialrelay')->__('Désolé, une erreure est survenu lors de la récupération de l\'étiquetes. Merci de contacter Mondial Relay ou de réessayer plus tard, erreur '.$etiquette->STAT.'.'));
	            }
	           exit(0);
	        } catch (Exception $e) {
	                Mage::Log('$Mage_Core_Exception : ',$e->getMessage());
	            $this->_getSession()->addError(Mage::helper('mondialrelay')->__('Désolé, une erreure est survenu lors de la récupération de l\'étiquetes. Merci de contacter Mondial Relay ou de réessayer plus tard.'));
	        }
        }
        // return $this->_redirect('mondialrelay/sales_impression/index');
         return $this->_redirectReferer();
    }

    public function printAction()
    {
        $shipmentId = $this->getRequest()->getParam('shipment_id');
        
        try {
            $urlEtiquette = $this->getEtiquetteUrl($shipmentId);
            $this->_processDownload('http://www.mondialrelay.fr' . $urlEtiquette, 'url');
           exit(0);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(Mage::helper('mondialrelay')->__('Désolé, une erreure est survenu lors de la récupération de l\'étiquetes. Merci de contacter Mondial Relay ou de réessayer plus tard'));
        }
        return $this->_redirectReferer();
    }
    
}