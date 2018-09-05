<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 28.08.18
 * Time: 15:35
 */
class Zhupanyn_Imgloader_Helper_Data extends Mage_Core_Helper_Abstract
{
    const IN_QUEUE  = 1;
    const RETRYING  = 2;
    const UPLOADED  = 3;
    const ERROR     = 4;

    public function getStatusArray()
    {
        return array(
            1=>$this->__('В очереди'),
            2=>$this->__('Повторная попытка'),
            3=>$this->__('Загружено'),
            4=>$this->__('Ошибка'),
        );
    }

    public function getTempPath($sku)
    {
        $fileName = Mage_Core_Model_File_Uploader::getCorrectFileName($sku.'.jpg');
        $dispretionPath = Mage_Core_Model_File_Uploader::getDispretionPath($fileName);
        $fileName = $dispretionPath . DS . $fileName;
        $path = $this->getMediaConfig()->getTmpMediaPath($fileName);

        return $path;
    }

    public function createTempDir($path)
    {
        $ioAdapter = new Varien_Io_File();
        $ioAdapter->setAllowCreateFolders(true);
        $destinationDirectory = dirname($path);
        try {
            $isCreate = $ioAdapter->createDestinationDir($destinationDirectory);
        } catch (Exception $e){
            $isCreate = false;
        }
        return $isCreate;
    }

    public function getMediaConfig()
    {
        return Mage::getSingleton('catalog/product_media_config');
    }

    public function getGmtDate()
    {
        return Mage::getModel('core/date')->gmtdate();
    }

    public function  getMediaAttribute($product)
    {
        $mediaAttribute = [];
        if (empty($product->getImage()) || $product->getImage() == 'no_selection')
            $mediaAttribute[] = 'image';
        if (empty($product->getSmallImage()) || $product->getSmallImage() == 'no_selection')
            $mediaAttribute[] = 'small_image';
        if (empty($product->getThumbnail()) || $product->getThumbnail() == 'no_selection')
            $mediaAttribute[] = 'thumbnail';
        return $mediaAttribute;
    }
}