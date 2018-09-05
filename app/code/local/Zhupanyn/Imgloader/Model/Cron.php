<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 31.08.18
 * Time: 15:44
 */

class Zhupanyn_Imgloader_Model_Cron
{
    public function startDownloadProductImages
    {
        $collection = Mage::getModel('zhupanyn_imgloader/list')->getCollection();
        $collection->addFieldToFilter('status', array('eq' => '1'));
        $this->setCollection($collection);



    }

}