<?php

class Zhupanyn_Imgloader_Adminhtml_Zhupanyn_ImgloaderController extends Mage_Adminhtml_Controller_Action
{
    public function listAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function listgridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initProduct()
    {
        $this->_title($this->__('Catalog'))
            ->_title($this->__('Manage Products'));

        $productId = (int)$this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if (!$productId) {
            if ($setId = (int)$this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }

            if ($typeId = $this->getRequest()->getParam('type')) {
                $product->setTypeId($typeId);
            }
        }

        $product->setData('_edit_mode', true);
        if ($productId) {
            try {
                $product->load($productId);
            } catch (Exception $e) {
                $product->setTypeId(Mage_Catalog_Model_Product_Type::DEFAULT_TYPE);
                Mage::logException($e);
            }
        }

        $attributes = $this->getRequest()->getParam('attributes');
        if ($attributes && $product->isConfigurable() &&
            (!$productId || !$product->getTypeInstance()->getUsedProductAttributeIds())) {
            $product->getTypeInstance()->setUsedProductAttributeIds(
                explode(",", base64_decode(urldecode($attributes)))
            );
        }

        // Required attributes of simple product for configurable creation
        if ($this->getRequest()->getParam('popup')
            && $requiredAttributes = $this->getRequest()->getParam('required')) {
            $requiredAttributes = explode(",", $requiredAttributes);
            foreach ($product->getAttributes() as $attribute) {
                if (in_array($attribute->getId(), $requiredAttributes)) {
                    $attribute->setIsRequired(1);
                }
            }
        }

        if ($this->getRequest()->getParam('popup')
            && $this->getRequest()->getParam('product')
            && !is_array($this->getRequest()->getParam('product'))
            && $this->getRequest()->getParam('id', false) === false) {

            $configProduct = Mage::getModel('catalog/product')
                ->setStoreId(0)
                ->load($this->getRequest()->getParam('product'))
                ->setTypeId($this->getRequest()->getParam('type'));

            /* @var $configProduct Mage_Catalog_Model_Product */
            $data = array();
            foreach ($configProduct->getTypeInstance()->getEditableAttributes() as $attribute) {

                /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
                if (!$attribute->getIsUnique()
                    && $attribute->getFrontend()->getInputType() != 'gallery'
                    && $attribute->getAttributeCode() != 'required_options'
                    && $attribute->getAttributeCode() != 'has_options'
                    && $attribute->getAttributeCode() != $configProduct->getIdFieldName()) {
                    $data[$attribute->getAttributeCode()] = $configProduct->getData($attribute->getAttributeCode());
                }
            }

            $product->addData($data)
                ->setWebsiteIds($configProduct->getWebsiteIds());
        }

        Mage::register('product', $product);
        Mage::register('current_product', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function indexAction()
    {
        /* @var $productsCollection Mage_Core_Model_Resource_Db_Collection_Abstract */
        /*$storeId = Mage::app()->getStore()->getId();
        $productsCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('image','small_image','media_gallery')
            ->addStoreFilter($storeId)
            ->addAttributeToFilter('image',array("notnull" => true ))
            ->addAttributeToFilter('small_image',array("notnull" => true ))
            ->addAttributeToFilter('media_gallery',array("notnull" => true ))
            //->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            //->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            //->addFieldToFilter('entity_id',905);
            //->addAttributeToSort('created_at', 'DESC')
            ->setPageSize(3);
        $str = $productsCollection->getSelect()->__toString();
        echo $str;
        var_dump($productsCollection->getData());

        $model_prod = Mage::getModel('catalog/product')->load(905);
        var_dump($model_prod->getData('media_gallery'));
        */

        /* @var $helper Zhupanyn_Imgloader_Helper_Data*/
        $helper = Mage::helper('zhupanyn_imgloader');

        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract*/
        $collection = Mage::getModel('zhupanyn_imgloader/list')->getCollection();
        $collection->addFieldToFilter('status', array('eq' => '1'));
        $collection->getSelect()->orWhere("status=2 and update_datetime < DATE_SUB('".$helper->getGmtDate()."', INTERVAL 1 DAY)");
        //$collection->getSelect()->limit(1);
        //echo  $str = $collection->getSelect()->__toString();
        //var_dump($collection->getData());

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
        var_dump($collection->getData());
    }

    public function newAction()
    {
        $this->loadLayout()->_setActiveMenu('catalog');
        $this->_addContent($this->getLayout()->createBlock('zhupanyn_imgloader/adminhtml_imgloader_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {

            $helper = Mage::helper('zhupanyn_imgloader');

            $file = $_FILES['file'];
            if ($file['size'] > 0) {
                if (file_exists($file['tmp_name'])) {
                    $imageLinkArray = [];
                    $handle = fopen($file['tmp_name'], "r");
                    if ($handle !== false) {
                        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $imageLinkArray[] = $row;
                        }
                        fclose($handle);
                    } else {
                        Mage::throwException($helper->__('Помилка при відкритті файла.'));
                    }

                    $prepareInsertArray = [];
                    foreach ($imageLinkArray as $value) {

                        if (is_null($value[0])){
                            continue;
                        }
                        $prepareInsertArray[] = array(
                            'sku'               =>  $value[0],
                            'create_datetime'   =>  $helper->getGmtDate(),
                            'img_url'           =>  $value[1],
                            //'status'            =>  rand(1,4)
                        );
                    }

                    if ( count($prepareInsertArray) > 0 ){
                        /* @var $model Zhupanyn_Imgloader_Model_List */
                        $model = Mage::getModel('zhupanyn_imgloader/list');
                        $model->insertMultiple($prepareInsertArray);
                    } else {
                        Mage::throwException($helper->__('Файл пустий!'));
                    }

                } else {
                    Mage::throwException($helper->__('Файл не існує. Спробуйте ще!'));
                }
            } else {
                Mage::throwException($helper->__('Додайте файл в форматі CSV!'));
            }

            $this->_getSession()->addSuccess($helper->__('Список картинок добавлений успішно!'));
            $this->_redirect('*/*/new');
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/new');
        }
    }
}