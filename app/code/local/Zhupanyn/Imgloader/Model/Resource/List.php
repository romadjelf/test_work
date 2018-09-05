<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 27.08.18
 * Time: 15:53
 */

class Zhupanyn_Imgloader_Model_Resource_List extends Mage_Core_Model_Resource_Db_Abstract
{
   public function _construct()
   {
      $this->_init('zhupanyn_imgloader/list','id');
   }
}