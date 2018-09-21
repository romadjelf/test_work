<?php

class Zhupanyn_Imgloader_Model_Image extends Varien_Object
{
    /**
     * Временный путь для сохранения загруженной картинки
     *
     * @var string
     */
    protected $tempPath = null;

    /**
     * Данные ответа http запроса
     *
     * @var string $responseVersion
     */
    protected $responseVersion = null;
    /**
     * @var string $responseCode
     */
    protected $responseCode = null;
    /**
     * @var string $responseMsg
     */
    protected $responseMsg = null;

    /**
     * Статус при загрузке картинки
     *
     * @var integer $itemStatus
     */
    protected $itemStatus = null;

    /**
     * Ошибка при загрузке картинки
     *
     * @var string $itemErrorText
     */
    protected $itemErrorText = null;

    /**
     * Размер загруженой картинки в байтах
     *
     * @var integer $itemImgSize
     */
    protected $itemImgSize = null;

    /**
     * Привязывать ли картинку к товару. Если ошибка при загрузке тогда $isAttachToProduct=false
     *
     * @var boolean $isAttachToProduct
     */
    protected $isAttachToProduct = true;

    /**
     * Чтение файла по ссылке
     *
     * @var Zhupanyn_Imgloader_Model_List $item
     * @throws Exception
     * @return boolean
     */
    public function read($item)
    {
        $this->prepareRead($item);

        $this->setFile(@file_get_contents($this->getImgUrl()));
        if ($this->getFile() === false) {
            list($this->responseVersion, $this->responseCode, $this->responseMsg) = explode(' ', $http_response_header[0], 3);
            $this->isAttachToProduct = false;
            return false;
        } else {
            return true;
        }
    }

    /**
     * Подготовка данных связаных с картинкой перед чтением файла по ссылке
     *
     * @var Zhupanyn_Imgloader_Model_List $item
     * @throws Exception
     */
    protected function prepareRead($item)
    {
        $this->setSku($item->getSku());
        $this->setImgUrl($item->getImgUrl());
        $this->setFile(null);
        $this->setTempPath();
    }

    /**
     * Проверка есть ли ошибка 404 в ответе запроса
     */
    public function checkResponseCode()
    {
        if ($this->responseCode == '404') {
            $this->itemStatus = Zhupanyn_Imgloader_Model_List::RETRYING;
        } else {
            $this->itemStatus = Zhupanyn_Imgloader_Model_List::ERROR;
            $this->itemErrorText = $this->responseMsg;
        }
    }

    /**
     * Создание временной темп папки для сохранения загруженной картинки
     *
     * @return boolean
     */
    public function createTempDir()
    {
        $path = $this->getTempPath();

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->setAllowCreateFolders(true);
        $destinationDirectory = dirname($path);
        try {
            $isCreate = $ioAdapter->createDestinationDir($destinationDirectory);
        } catch (Exception $e) {
            $isCreate = false;
        }
        if (!$isCreate) {
            $this->itemStatus = Zhupanyn_Imgloader_Model_List::ERROR;
            $this->itemErrorText = $this->getHelper()->__('A temporary folder is not created for the path: ') . $path;
            $this->isAttachToProduct = false;
        }
        return $isCreate;
    }

    /**
     * Получение пути к картинке в временной папке
     *
     * @return string
     */
    public function getTempPath()
    {
        if (is_null($this->tempPath)) {
            $this->setTempPath();
        }
        return $this->tempPath;
    }

    /**
     * Создание пути для картинки в временной папке
     */
    protected function setTempPath()
    {
        $sku = $this->getSku();
        $fileName = Mage_Core_Model_File_Uploader::getCorrectFileName($sku . '.jpg');
        $dispertionPath = Mage_Core_Model_File_Uploader::getDispretionPath($fileName);
        $fileName = $dispertionPath . DS . $fileName;
        $this->tempPath = $this->getMediaConfig()->getTmpMediaPath($fileName);
    }

    /**
     * Запись загруженной картинки в файл по пути $this->tempPath
     */
    public function write()
    {
        $path = $this->getTempPath();

        $file_size = file_put_contents($path, $this->getFile());
        if ($file_size === false) {
            $this->itemStatus = Zhupanyn_Imgloader_Model_List::ERROR;
            $this->itemErrorText = $this->getHelper()->__('File is not uploaded to a temporary folder: ') . $path;
            $this->isAttachToProduct = false;
        } else {
            $this->itemStatus = Zhupanyn_Imgloader_Model_List::UPLOADED;
            $this->itemImgSize = $file_size;
        }
    }

    /**
     * @return int
     */
    public function getItemStatus()
    {
        return $this->itemStatus;
    }

    /**
     * @return string
     */
    public function getItemErrorText()
    {
        return $this->itemErrorText;
    }

    /**
     * @return int
     */
    public function getItemImgSize()
    {
        return $this->itemImgSize;
    }

    /**
     * @return bool
     */
    public function getIsAttachToProduct()
    {
        return $this->isAttachToProduct;
    }

    /**
     * Retrive media config
     *
     * @return Mage_Catalog_Model_Product_Media_Config
     */
    protected function getMediaConfig()
    {
        return Mage::getSingleton('catalog/product_media_config');
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function getHelper()
    {
        return Mage::helper('zhupanyn_imgloader');
    }
}