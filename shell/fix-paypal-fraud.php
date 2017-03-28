<?php
require_once 'abstract.php';
 
class Gourmetbox_Shell_FixPaypalFraud extends Mage_Shell_Abstract
{
    protected $_argname = array();
 
    public function __construct()
    {
        parent::__construct();
 
        // Time limit to infinity
        set_time_limit(0);     
 
        // Get command line argument named "argname"
        // Accepts multiple values (comma separated)
        if ($this->getArg('argname')) {
            $this->_argname = array_merge(
                $this->_argname,
                array_map(
                    'trim',
                    explode(',', $this->getArg('argname'))
                )
            );
        }
    }
 
    // Shell script point of entry
    public function run()
    {
        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'fraud')
            ->addFieldToFilter(
                    'created_at', 
                    array('gt' => Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime('-1 month')))
            );
        
        Mage::log($orders->getSize() . ' suspected frauds order will be now updated', null, 'paypal_fraud_update.log');

        foreach ($orders as $order) {

            $orderId = $order->getId();

            $fullOrder = Mage::getModel('sales/order')->load($orderId);
            $fullOrder->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
            $fullOrder->setStatus('processing', false);
            $fullOrder->save();

            try {
                
                if (!$fullOrder->canInvoice()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
                }

                $invoice = Mage::getModel('sales/service_order', $fullOrder)->prepareInvoice();

                if (!$invoice->getTotalQty()) {
                    Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                }

                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                $invoice->register();

                $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder());
                $transactionSave->save();
                
            } catch (Mage_Core_Exception $e) {
            }
        }
    }
 
    // Usage instructions
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f scriptname.php -- [options]
 
  --argname <argvalue>       Argument description
 
  help                   This help
 
USAGE;
    }
}
// Instantiate
$shell = new Gourmetbox_Shell_FixPaypalFraud();
 
// Initiate script
$shell->run();
