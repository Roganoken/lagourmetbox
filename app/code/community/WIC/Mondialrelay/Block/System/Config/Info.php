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

class WIC_Mondialrelay_Block_System_Config_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
        
        $html =  '<div style="background-color:#a20039;border:1px solid #FFFFFF;overflow:hidden;padding:10px;margin-bottom:10px;border-radius:10px;min-width:607px;">'
				.'	<div style="float:left;width:105px">'
        		.'		<img src="http://www.mondialrelay.fr/media/10669/mondial-relay-logo.png">'
        		.'	</div>'
        		.'	<div style="float:left;width:43%;padding:0 10px;color:#FFFFFF;">'
				.'		<p>' . Mage::helper('mondialrelay')->__('Le service clientèle de Mondial Relay est disponible pour vous aider et répondre à vos questions.') . '</p>'
				.'		<p>' . Mage::helper('mondialrelay')->__('Contactez-nous par Email :') . ' <a href="mailto:servicecommercial@mondialrelay.com">servicecommercial@mondialrelay.com</a><br/>'
				.'		' . Mage::helper('mondialrelay')->__('Ou par téléphone :').'  <tel>0892 707 617 (0,34&euro; TTC/min)</tel></p>'
				.'		<p>' . Mage::helper('mondialrelay')->__('Pour plus d\'information, veuillez lire la documentation en cliquant sur le lien suivant :') . '<br/>'
				.'		<a target="_blank" href="http://www.mondialrelay.fr/public/mr_faq.aspx">' . Mage::helper('mondialrelay')->__('Documentation Magento Module Mondial Relay') . '</a></p>'
				.'	</div>'
				.'	<div style="float:right;width:220px">'
				.'		<img src="http://www.mondialrelay.fr/img/FR/BLOCCPourtoi_FR.gif">'
				.'	</div>'
				.'</div>';

        return $html;
    }
}