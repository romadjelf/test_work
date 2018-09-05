<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 27.08.18
 * Time: 15:27
 */

class Zhupanyn_Imgloader_Model_List extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('zhupanyn_imgloader/list');
    }

    public function insertMultiple($rows)
    {
        $tableName = $this->getResource()->getMainTable();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $return = $write->insertMultiple($tableName,$rows);
        $fd = 1;
        return $this;
    }
}