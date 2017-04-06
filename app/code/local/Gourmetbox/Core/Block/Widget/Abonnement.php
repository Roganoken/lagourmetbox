<?php

class Gourmetbox_Core_Block_Widget_Abonnement extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * Internal constructor
     *
     */
    protected function _construct()
    {
        $this->setTemplate('widget/abonnement.phtml');
        parent::_construct();
    }
    
    public function getProduct($widget, $i)
    {
        $product = null;
        
        $productWidget = $widget->getData('abonnement' . $i);
        
        if ($productWidget) {
            $productExploded = explode("/", $productWidget);
            $productId = $productExploded[1];
            $product = Mage::getModel('catalog/product')->load($productId);
        }
        
        return $product;
    }
    
    public function getProductAbonnement($product)
    {
        $productAbonnement = null;
        $productsAbonnement = $product->getTypeInstance(true)->getAssociatedProducts($product);
        
        foreach ($productsAbonnement as $productAbonnement) {
            return $productAbonnement;
        }
        
        return $productAbonnement;
    }
}
