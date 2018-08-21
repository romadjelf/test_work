<?php
class Zhupanyn_Action_Model_Resource_Action_Product extends Mage_Core_Model_Resource_Db_Abstract
{
   public function _construct()
   {
      $this->_init('zhupanyn_action/action_product','id');
   }
}
?>