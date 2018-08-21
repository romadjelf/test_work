<?php
class Zhupanyn_Newproducts_Block_List extends Mage_Core_Block_Template
{
   public function getCollection()
   {
      $storeId = Mage::app()->getStore()->getId();
      $productsCollection = Mage::getModel('catalog/product')->getCollection()
         ->addAttributeToSelect('*')
         ->addStoreFilter($storeId)
         ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
         ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
         ->addAttributeToSort('created_at', 'DESC')
         ->setPageSize(12);
      return $productsCollection;
   }
}
?>
