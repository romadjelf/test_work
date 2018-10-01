<?php

class Zhupanyn_Youtube_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    public function getHelper()
    {
        return Mage::helper('zhupanyn_youtube');
    }

    public function catalogSetup()
    {
        return Mage::getModel('catalog/resource_setup', 'core_setup');
    }
}