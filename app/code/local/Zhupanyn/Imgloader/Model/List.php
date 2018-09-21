<?php

class Zhupanyn_Imgloader_Model_List extends Mage_Core_Model_Abstract
{
    const IN_QUEUE  = 1;
    const RETRYING  = 2;
    const UPLOADED  = 3;
    const ERROR     = 4;

    /**
     * Массив ссылок и sku из загруженного файла csv
     *
     * @var array
     */
    protected $imageLinkArray = [];

    /**
     * Подготовленный массива $imageLinkArray для сохранения в таблицу zhupanyn_imgloader_list
     *
     * @var array
     */
    protected $prepareInsertArray = [];

    /**
     * Ошибки валидации поля(полей) формы добавления csv файла
     *
     * @var array
     */
    protected $errors = [];

    public function _construct()
    {
        $this->_init('zhupanyn_imgloader/list');
    }

    /**
     * Сохранение данных из csv файла в таблицу zhupanyn_imgloader_list
     *
     * @param boolean $is_validate
     * @throws Exception
     */
    public function saveImageLinks($is_validate=true)
    {
        $valid = true;
        if ($is_validate) {
            $valid = $this->validateImageLinks();
        }
        if ($valid) {
            $this->prepareImageLinks();
            /* @var boolean $result */
            $result = $this->getResource()->insertMultiple($this->prepareInsertArray);
            if (!$result) {
                Mage::throwException($this->getHelper()->__('The list of pictures is not added!'));
            }
        } else {
            $errorMessage = $this->getErrorMessage();
            Mage::throwException($errorMessage);
        }
    }

    /**
     * Проверка полей формы загрузки файла csv
     */
    public function validateImageLinks()
    {
        $valid = $this->validFileCsv();
        if (!$valid) {
            return false;
        }
        return true;
    }

    /**
     * Проверка загруженного csv файла
     */
    protected function validFileCsv()
    {
        $helper = $this->getHelper();
        $file = $this->getFileCsv();
        if (isset($file)){
            if ($file['size'] > 0) {
                if (file_exists($file['tmp_name'])) {
                    return true;
                } else {
                    $this->errors[] = $helper->__('The file does not exist. Try again!');
                    return false;
                }
            } else {
                $this->errors[] = $helper->__('Add file in CSV format!');
                return false;
            }
        } else {
            $this->errors[] = $helper->__('Add file as file_csv property!');
            return false;
        }
    }

    /**
     * Подготовка данных из csv файла для записи в таблицу zhupanyn_imgloader_list
     */
    protected function prepareImageLinks()
    {
        if (empty($this->imageLinkArray)){
            $this->setImageLinkArray();
        }
        $this->setPrepareInsertArray();

        if (empty($this->prepareInsertArray)){
            Mage::throwException($this->getHelper()->__('File is empty!'));
        }
    }

    /**
     * Считывание данных з csv файла и запись данных в массив $imageLinkArray
     */
    protected function setImageLinkArray()
    {
        $helper = $this->getHelper();
        $file = $this->getFileCsv();
        $handle = fopen($file['tmp_name'], "r");
        if ($handle !== false) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $this->imageLinkArray[] = $row;
            }
            fclose($handle);
        } else {
            Mage::throwException($helper->__('Error when opening file!'));
        }
    }

    /**
     * Задание значений массиву $prepareInsertArray
     */
    protected function setPrepareInsertArray()
    {
        $gmtDate = $this->getGmtDate();
        foreach ($this->imageLinkArray as $value) {
            if (is_null($value[0])){
                continue;
            }
            $this->prepareInsertArray[] = array(
                'sku'               =>  $value[0],
                'create_datetime'   =>  $gmtDate,
                'img_url'           =>  $value[1]
            );
        }
    }

    protected function getErrorMessage()
    {
        $msg = '';
        for ($i=0;$i<count($this->errors);$i++) {
            $n = $i + 1;
            $msg .= $n.". ".$this->errors[$i].'<br>';
        }
        return $msg;
    }

    protected function getHelper()
    {
        return Mage::helper('zhupanyn_imgloader');
    }

    public function getGmtDate()
    {
        return Mage::getModel('core/date')->gmtdate();
    }

    /**
     * Загрузка всех картинок которые в очереди или с повторной загрузкой
     */
    public function uploadPictures()
    {
        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract*/
        $collection = $this->getPicturesDownloadCollection();
        /* @var $image Zhupanyn_Imgloader_Model_Image*/
        $image = Mage::getModel('zhupanyn_imgloader/image');

        foreach ($collection as $item) {

            if ($image->read($item)) {
                if ($image->createTempDir()) {
                    $image->write();
                }
            } else {
                $image->checkResponseCode();
            }
            $item->saveUploadInformation($image);

            if ($image->getIsAttachToProduct()){
                $item->attachPictureToProduct($image);
            }
        }
    }

    /**
     * Получение коллекции не загруженых картинок или картинок с повторной загрузкой через сутки
     *
     * @return Zhupanyn_Imgloader_Model_Resource_List_Collection
     */
    public function getPicturesDownloadCollection()
    {
        $picturesDownloadCollection = $this->getCollection();
        $picturesDownloadCollection->addFieldToFilter('status', array('eq' => '1'));
        $picturesDownloadCollection->getSelect()->orWhere("status=2 and update_datetime < DATE_SUB('".$this->getGmtDate()."', INTERVAL 1 DAY)");
        return $picturesDownloadCollection;
    }

    /**
     * Сохранение информации по загруженной картинке
     *
     * @param Zhupanyn_Imgloader_Model_Image $image
     * @throws Exception
     */
    public function saveUploadInformation($image)
    {
        if (!is_null($image->getItemStatus())) {
            $this->setStatus($image->getItemStatus());
        }
        if (!is_null($image->getItemErrorText())) {
            $this->setErrorText($image->getItemErrorText());
        }
        if (!is_null($image->getItemImgSize())) {
            $this->setImgSize($image->getItemImgSize());
        }
        $this->setUpdateDatetime($this->getGmtDate());
        $this->save();
    }

    /**
     * Привязка картинки к товару: добавление в media_gallery и картинок основной, маленькой и картинки иконки
     *
     * @param Zhupanyn_Imgloader_Model_Image $image
     * @throws Exception
     */
    public function attachPictureToProduct($image)
    {
        $path = $image->getTempPath();
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$image->getSku());
        $mediaAttribute = $this->getMediaAttribute($product);

        try {
            $product->addImageToMediaGallery ($path, $mediaAttribute, true, false );
            $product->save();
        } catch (Exception $e) {
            $this->setStatus($image->getItemStatus());
            $this->setErrorText($e->getMessage().'<br>'.$this->getHelper()->__('Path to picture in temp folder: ').$path);
            $this->save();
        }
    }

    /**
     * Добавление в массив аттрибутов основной и маленькой картинки, и картинки иконки если не заданы в продукте
     *
     * @param Mage_Catalog_Model_Product $product
     * @throws Exception
     */
    public function getMediaAttribute($product)
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

    /**
     * Возвращение массива статусов загруженных картинок
     *
     *
     * @return array
     */
    public static function getStatusArray()
    {
        $helper = self::getHelper();
        return array(
            self::IN_QUEUE  =>  $helper->__('In queue'),
            self::RETRYING  =>  $helper->__('Retrying'),
            self::UPLOADED  =>  $helper->__('Uploaded'),
            self::ERROR     =>  $helper->__('Error'),
        );
    }
}