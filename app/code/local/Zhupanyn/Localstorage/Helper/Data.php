<?php
class Zhupanyn_Localstorage_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getDomain()
    {
        $cookie = Mage::getModel('core/cookie');
        return $cookie->getDomain();
    }

    public function getTime()
    {
        $time = Mage::getStoreConfig('catalog/recently_products/viewed_time');
        return $time;
    }
}