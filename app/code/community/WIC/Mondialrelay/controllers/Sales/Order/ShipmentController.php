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
 * Adminhtml sales order shipment controller
 *
 */

require_once 'Mage/Adminhtml/controllers/Sales/Order/ShipmentController.php';
class WIC_Mondialrelay_Sales_Order_ShipmentController extends Mage_Adminhtml_Sales_Order_ShipmentController
{

    /**
     * Save shipment
     * We can save only new shipment. Existing shipments are not editable
     */
    
	public function getConfigData($field)
	{
        $path = 'carriers/pointsrelais/'.$field;
        return Mage::getStoreConfig($path, Mage::app()->getStore());
	}
    
    public function saveAction()
    {
        $data = $this->getRequest()->getPost('shipment');
		$shipment = $this->_initShipment();
		if (!$shipment) {
			$this->_forward('noRoute');
			return;
		}
		$class = get_class($shipment);
		$_order = $shipment->getOrder();
        $_shippingMethod = explode("_",$_order->getShippingMethod());
		
		if( preg_match( '/^pointsrelais/', $_shippingMethod[0], $m) ) {
        try {
            if ($shipment) {               
                
                $adress = $_order->getShippingAddress();				

				$street = $adress->getStreet();
				if(!$street || (isset($street[0]) && !$street[0])) {	
					$shipping_adress = $_order->getShippingAddress();
					$shipping_adress = Mage::getModel('customer/address')->load($shipping_adress->getData("customer_address_id"));		
					$adress = $shipping_adress;
				}				
				$package_weightTmp = $_order->getWeight()*1000;				
				if($package_weightTmp < 100){
					$package_weightTmp = 100;
				}
				$packageTmp[0] = 1;
				
				$telephone = '';
				$country = trim($this->removeaccents($adress->getCountryId()));
				
				if(isset(WIC_Mondialrelay_Helper_Data::$indicator[$country])) {
					$o = array('-',' ','/');
					$n = array('','','');
					$indic = WIC_Mondialrelay_Helper_Data::$indicator[$country];					
					$tel = trim(str_replace($o, $n, $adress->getTelephone()));
					
					// test if 9 digit
					if(strlen($tel) <= 9) {
						$tel = sprintf("%010s", $tel);
					}
					// test if valid number
					$re = '/^((00|\+)'.$indic.'|0)[0-9][0-9]{8}$/';					
					$test = preg_match($re, $tel, $m);					
					if($test == 1) {
						$telephone = $tel;
					}
				}
				
				$params = array(
				   'Enseigne'       => Mage::getStoreConfig('shipping/mondialrelay/enseigne'),
				   'ModeCol'        => 'CCC',
				   'ModeLiv'        => '',
				   'Expe_Langage'   => substr(trim($this->removeaccents($this->getConfigData('pays_enseigne'))),0,2),
				   'Expe_Ad1'       => trim($this->removeaccents($this->getConfigData('adresse1_enseigne'))),
				   'Expe_Ad3'       => trim($this->removeaccents($this->getConfigData('adresse3_enseigne'))),
				   'Expe_Ad4'       => trim($this->removeaccents($this->getConfigData('adresse4_enseigne'))),
				   'Expe_Ville'     => trim($this->removeaccents($this->getConfigData('ville_enseigne'))),
				   'Expe_CP'        => $this->getConfigData('cp_enseigne'),
				   'Expe_Pays'      => trim($this->removeaccents($this->getConfigData('pays_enseigne'))),
				   'Expe_Tel1'      => '',
				   'Expe_Tel2'      => '',
				   'Expe_Mail'      => $this->getConfigData('mail_enseigne'),
				   'Dest_Langage'   => 'FR',
				   'Dest_Ad1'       => trim($this->removeaccents($adress->getFirstname() . ' ' . $adress->getLastname())),
				   'Dest_Ad2'       => trim($this->removeaccents($adress->getCompagny())),
				   'Dest_Ad3'       => trim($this->removeaccents($street[0])),
				   'Dest_Ad4'       => trim($this->removeaccents($street[1])),                                   
				   'Dest_Ville'     => trim($this->removeaccents($adress->getCity())),
				   'Dest_CP'        => $adress->getPostcode(),
				   'Dest_Pays'      => $country,
				   'Dest_Tel1'      => $telephone,
				   'Dest_Mail'      => $_order->getCustomerEmail(),
				   'Poids'          => $package_weightTmp,
				   'NbColis'        => $packageTmp[0],
				   'CRT_Valeur'     => '0',				 
				);
                if ($_shippingMethod[0] == 'pointsrelaiscd')  {
                    $params['ModeLiv'] = 'DRI';
					$params['LIV_Rel_Pays'] = $adress->getCountryId();
					$params['LIV_Rel'] = $_shippingMethod[1];
                }
				elseif ($_shippingMethod[0] == 'pointsrelais')  {
                    $params['ModeLiv'] = '24R';
					$params['LIV_Rel_Pays'] = $adress->getCountryId();
					$params['LIV_Rel'] = $_shippingMethod[1];
                }
				elseif ($_shippingMethod[0] == 'pointsrelaisld1') {
					$params['ModeLiv'] = 'LD1';					
					$params['ModeLiv'] = 'HOM';					
				}
				elseif ($_shippingMethod[0] == 'pointsrelaislds') {
                   	$params['ModeLiv'] = 'LDS';
                }
                    
				$select = "";
				foreach($params as $key => $value){
					$value = strtr($value,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ?!,;.:', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY      ');
					$select .= "\t".'<option value="'.$key.'">' . $value.'</option>'."\r\n";
				}
				
				$code = implode("",$params);
				$code .= Mage::getStoreConfig('shipping/mondialrelay/cle');
				
				//On le rajoute aux paramÃ¨tres
				$params["Security"] = strtoupper(md5($code));
				
				// On se connecte
				$client = Mage::getSingleton('mondialrelay/soap');
				
				// Et on effectue la requÃ¨te
				$expedition = $client->WSI2_CreationExpedition($params)->WSI2_CreationExpeditionResult;
				
				$track = Mage::getModel('sales/order_shipment_track')
					->setNumber($expedition->ExpeditionNum)
					->setCarrier('Mondial Relay')
					->setCarrierCode($_shippingMethod[0])
					->setTitle('Mondial Relay')
					->setPopup(1);
				$shipment->addTrack($track);				
				
				// echo '<pre>';
				// print_r($params);
				// print_r($adress->getData());
				// print_r($expedition);
				// echo Mage::Helper('mondialrelay')->convertStatToTxt($expedition->STAT);
				// die();
				
				
				if(!isset($expedition->ExpeditionNum)) {			
					$error = Mage::Helper('mondialrelay')->convertStatToTxt($expedition->STAT);
					$this->_getSession()->addError($this->__('Can not save shipment : '.$error));
					$this->_redirect('adminhtml/sales_order/view', array('order_id' => $shipment->getOrderId()));
					return '';
				}
				
				// echo '<pre>';
				// print_r($params);
				// print_r($expedition);
				// echo Mage::Helper('mondialrelay')->convertStatToTxt($expedition->STAT);
				// die();
                $shipment->register();
                $comment = '';
                if (!empty($data['comment_text'])) {
                    $shipment->addComment($data['comment_text'], isset($data['comment_customer_notify']));
                    $comment = $data['comment_text'];
                }

                if (!empty($data['send_email'])) {
                    $shipment->setEmailSent(true);
                }

                $this->_saveShipment($shipment);
                $shipment->sendEmail(!empty($data['send_email']), $comment);
                $this->_getSession()->addSuccess($this->__('Shipment was successfully created.'));
                $this->_redirect('adminhtml/sales_order/view', array('order_id' => $shipment->getOrderId()));
                return;
            	
            }else {
                $this->_forward('noRoute');
                return;
            }
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addError($this->__('Can not save shipment: '.$e->getMessage()));
        }
		}
		else{
			if (!$shipment) {
                $this->_forward('noRoute');
                return;
            }
			        $data = $this->getRequest()->getPost('shipment');
					if (!empty($data['comment_text'])) {
						Mage::getSingleton('adminhtml/session')->setCommentText($data['comment_text']);
					}

					try {
						$shipment->register();
						$comment = '';
						if (!empty($data['comment_text'])) {
							$shipment->addComment(
								$data['comment_text'],
								isset($data['comment_customer_notify']),
								isset($data['is_visible_on_front'])
							);
							if (isset($data['comment_customer_notify'])) {
								$comment = $data['comment_text'];
							}
						}

						if (!empty($data['send_email'])) {
							$shipment->setEmailSent(true);
						}

						$shipment->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
						$responseAjax = new Varien_Object();
						$isNeedCreateLabel = isset($data['create_shipping_label']) && $data['create_shipping_label'];
						if ($isNeedCreateLabel) {
							if ($this->_createShippingLabel($shipment)) {
								$this->_getSession()
									->addSuccess($this->__('The shipment has been created. The shipping label has been created.'));
								$responseAjax->setOk(true);
							}
						} else {
							$this->_getSession()
								->addSuccess($this->__('The shipment has been created.'));
						}
						$this->_saveShipment($shipment);
						$shipment->sendEmail(!empty($data['send_email']), $comment);
						Mage::getSingleton('adminhtml/session')->getCommentText(true);
					} catch (Mage_Core_Exception $e) {
						if ($isNeedCreateLabel) {
							$responseAjax->setError(true);
							$responseAjax->setMessage($e->getMessage());
						} else {
							$this->_getSession()->addError($e->getMessage());
							$this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
						}
					} catch (Exception $e) {
						Mage::logException($e);
						if ($isNeedCreateLabel) {
							$responseAjax->setError(true);
							$responseAjax->setMessage(Mage::helper('sales')->__('An error occurred while creating shipping label.'));
						} else {
							$this->_getSession()->addError($this->__('Cannot save shipment.'));
							$this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
						}

					}
					if ($isNeedCreateLabel) {
						$this->getResponse()->setBody($responseAjax->toJson());
					} else {
						$this->_redirect('*/sales_order/view', array('order_id' => $shipment->getOrderId()));
					}
		}
		return ;
    }
    
    Function getPackage($_order,$shipmentType){ 
    	$nbtoreturn = 1;
    	$totalLength = 0;
    	$length = 0;
    	$weight = 0;
    	$lengthTmp = 0;
    	$weightTmp = 0;
    	$decrementFlag = false;
    	foreach ($_order->getAllItems() as $item) {
    			if($decrementFlag){
    				$length += $lengthTmp;
    				$weight += $weightTmp;
    				$decrementFlag = false;
    			}
	            if ($productId = $item->getProductId()) {
	                $product = Mage::getModel('catalog/product')->load($productId);
	                if($product->getDevelopedLength()){
	                	$length += $product->getDevelopedLength();
	                	$lengthTmp = $product->getDevelopedLength();
	                	$totalLength += $product->getDevelopedLength();
	                }else{
	                	$length += $this->getConfigData('default_developed_length');
	                	$lengthTmp = $this->getConfigData('default_developed_length');
	                	$totalLength += $this->getConfigData('default_developed_length');
	                }
	                $weight += $product->getWeight();

	                if($shipmentType == 'LDS'){
	                	if(($weightTmp > 130) || ($lengthTmp > 450)){
	                		$decrementFlag = true;
	                		$nbtoreturn++;
					    	$length = 0;
					    	$weight = 0;
	                	}
	                }else{
	                	if(($weightTmp > 60) || ($lengthTmp > 250)){
	                		$decrementFlag = true;
	                		$nbtoreturn++;
					    	$length = 0;
					    	$weight = 0;
	                	}
	                
	                }
	            }
	    }
		return array($nbtoreturn,$totalLength);
   	}
   
    Function removeaccents($string){ 
	   $stringToReturn = str_replace( 
	   array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý','/','\xa8'), 
	   array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y',' ','e'), $string);
	   // Remove all remaining other unknown characters
	$stringToReturn = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $stringToReturn);
	$stringToReturn = preg_replace('/^[\-]+/', '', $stringToReturn);
	$stringToReturn = preg_replace('/[\-]+$/', '', $stringToReturn);
	$stringToReturn = preg_replace('/[\-]{2,}/', ' ', $stringToReturn);
	return $stringToReturn;
   } 
    
}
