<?php
class Zhupanyn_Action_Helper_Data extends Mage_Core_Helper_Abstract
{

   public function getIsActiveArray()
   {
      return array(
         0=>$this->__('No'),
         1=>$this->__('Yes')
      );
   }

   public function getIsActiveFormArray()
   {
      return array('-1'=>$this->__('-- Виберіть значення --')) + $this->getIsActiveArray();
   }

   public function getStatusArray()
   {
      return array(
         1=>$this->__('Off'),
         2=>$this->__('On'),
         3=>$this->__('Close')
      );
   }

}
?>
