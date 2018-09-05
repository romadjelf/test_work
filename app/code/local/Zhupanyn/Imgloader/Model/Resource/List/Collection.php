<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 27.08.18
 * Time: 15:58
 */

class Zhupanyn_Imgloader_Model_Resource_List_Collection extends
Mage_Core_Model_Resource_Db_Collection_Abstract
{
   public function _construct()
   {
      $this->_init('zhupanyn_imgloader/list');
   }
}