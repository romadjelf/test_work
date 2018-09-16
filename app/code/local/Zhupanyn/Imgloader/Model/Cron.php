<?php

class Zhupanyn_Imgloader_Model_Cron
{
    public function startDownloadProductImages()
    {
        /* @var $helper Zhupanyn_Imgloader_Helper_Data*/
        $helper = Mage::helper('zhupanyn_imgloader');

        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract*/
        $collection = Mage::getModel('zhupanyn_imgloader/list')->getCollection();
        $collection->addFieldToFilter('status', array('eq' => '1'));
        $collection->getSelect()->orWhere("status=2 and update_datetime < DATE_SUB('".$helper->getGmtDate()."', INTERVAL 1 DAY)");

        foreach ($collection as $item) {
            $sku = $item->getSku();
            $url = $item->getImgUrl();
            $path = $helper->getTempPath($sku);
            $isError = false;
            $file = @file_get_contents($url);
            if ($file === false) {
                list($version,$code,$msg) = explode(' ',$http_response_header[0],3);
                if ($code == '404') {
                    $item->setStatus($helper::RETRYING);
                } else {
                    $item->setStatus($helper::ERROR);
                    $item->setErrorText($msg);
                }
                $isError = true;
            } else {
                $isCreateDir = $helper->createTempDir($path);
                if ($isCreateDir) {
                    $file_size = file_put_contents($path,$file);
                    if ($file_size === false) {
                        $item->setStatus($helper::ERROR);
                        $item->setErrorText('Файл не загружений в тимчасову папку '.$path);
                        $isError = true;
                    } else {
                        $item->setStatus($helper::UPLOADED);
                        $item->setImgSize($file_size);
                    }
                } else {
                    $item->setStatus($helper::ERROR);
                    $item->setErrorText('Тимчасова папка не створена для шляху '.$path);
                    $isError = true;
                }
            }
            $item->setUpdateDatetime($helper->getGmtDate());
            $item->save();
            if ($isError){
                continue;
            }
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
            $mediaAttribute = $helper->getMediaAttribute($product);

            try {
                $product->addImageToMediaGallery ($path, $mediaAttribute, true, false );
                $product->save();
            } catch (Exception $e) {
                $item->setStatus($helper::ERROR);
                $item->setErrorText($e->getMessage().' Шлях до картинки в темп: '.$path);
                $item->save();
            }
        }
    }

}