<?php

class Zhupanyn_Imgloader_Model_Resource_List extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('zhupanyn_imgloader/list','id');
    }

    /**
     * Сохранение всех данных полученных из csv файла в таблицу zhupanyn_imgloader_list одним запросом в базу данных
     *
     * @param array $rows
     * @return boolean
     * @throws Exception
     */
    public function insertMultiple($rows)
    {
        $tableName = $this->getMainTable();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $return = $write->insertMultiple($tableName,$rows);
        if ($return > 0) {
            return true;
        } else {
            return false;
        }
    }
}