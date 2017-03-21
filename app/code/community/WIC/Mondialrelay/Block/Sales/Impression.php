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
class WIC_Mondialrelay_Block_Sales_Impression extends Mage_Adminhtml_Block_Widget_Grid_Container
{


    public function __construct()
    {
        $this->_blockGroup = 'mondialrelay';
        $this->_controller = 'sales_shipment';
        $this->_headerText = Mage::helper('mondialrelay')->__('Impressions des étiquettes Mondial Relay');
        parent::__construct();
        $this->_removeButton('add');
    }

}