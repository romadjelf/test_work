<?php
class Zhupanyn_Action_Model_Action extends Mage_Core_Model_Abstract {

   public function _construct()
   {
      $this->_init('zhupanyn_action/action');
   }

   public function EvenPrefix()
   {
      return $this->_eventPrefix;
   }
}
?>
