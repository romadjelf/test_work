<?php

class Zhupanyn_Youtube_Model_Resource_Youtube extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('zhupanyn_youtube/youtube','id_product');
    }
}