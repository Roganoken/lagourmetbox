<?php

/* Disable onepagecheckout extension */
Mage::getConfig()->saveConfig('onepagecheckout/general/enabled', 0, 'default', 0);
Mage::getConfig()->saveConfig('onepagecheckout/general/enabled', 0, 'websites', 1);
