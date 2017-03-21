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
 * System config form block
 *
 */
class WIC_Mondialrelay_Block_System_Config_Form extends Mage_Adminhtml_Block_System_Config_Form
{   
 
    /**
     * Enter description here...
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'export'        => Mage::getConfig()->getBlockClassName('adminhtml/system_config_form_field_export'),
            'import'        => Mage::getConfig()->getBlockClassName('adminhtml/system_config_form_field_import'),
            'allowspecific' => Mage::getConfig()->getBlockClassName('adminhtml/system_config_form_field_select_allowspecific'),
            'image'         => Mage::getConfig()->getBlockClassName('adminhtml/system_config_form_field_image'),
            'export_pointsrelais'         => Mage::getConfig()->getBlockClassName('mondialrelay/system_config_form_field_exportpointsrelais'),
            'export_pointsrelaiscd'         => Mage::getConfig()->getBlockClassName('mondialrelay/system_config_form_field_exportpointsrelaiscd'),
            'export_pointsrelaisld1'         => Mage::getConfig()->getBlockClassName('mondialrelay/system_config_form_field_exportpointsrelaisld1'),
            'export_pointsrelaislds'         => Mage::getConfig()->getBlockClassName('mondialrelay/system_config_form_field_exportpointsrelaislds')
        );
    }

    }
