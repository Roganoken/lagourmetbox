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
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('mondialrelay_pointsrelais')};
DROP TABLE IF EXISTS {$this->getTable('mondialrelay_pointsrelaisld1')};
DROP TABLE IF EXISTS {$this->getTable('mondialrelay_pointsrelaislds')};
DELETE FROM {$this->getTable('core/config_data')} WHERE path like 'carriers/pointsrelais/%';
DELETE FROM {$this->getTable('core/resource')} WHERE code like 'pointsrelais_setup';
");

$installer->endSetup();
