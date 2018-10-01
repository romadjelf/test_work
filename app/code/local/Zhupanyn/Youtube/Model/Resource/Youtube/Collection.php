<?php

class Zhupanyn_Youtube_Model_Resource_Youtube_Collection extends
    Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('zhupanyn_youtube/youtube');
    }
}