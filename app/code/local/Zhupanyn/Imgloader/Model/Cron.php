<?php

class Zhupanyn_Imgloader_Model_Cron
{
    /**
     * Запуск процесса скачивания картинок и привязка их к соответствующим товарам по sku товара
     */
    public function startDownloadProductImages()
    {
        /* @var $model Zhupanyn_Imgloader_Model_List*/
        $model = Mage::getModel('zhupanyn_imgloader/list');
        $model->uploadPictures();
    }
}