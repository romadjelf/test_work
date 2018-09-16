<?php

class Zhupanyn_Imgloader_Model_Resource_List extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
      $this->_init('zhupanyn_imgloader/list','id');
    }

    public function insertMultiple($rows)
    {
        $tableName = $this->getMainTable();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $return = $write->insertMultiple($tableName,$rows);
        return $this;
    }
}