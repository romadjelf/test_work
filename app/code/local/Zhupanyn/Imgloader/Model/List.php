<?php

class Zhupanyn_Imgloader_Model_List extends Mage_Core_Model_Abstract
{
    protected $imageLinkArray = [];
    protected $prepareInsertArray = [];
    protected $errors = [];

    public function _construct()
    {
        $this->_init('zhupanyn_imgloader/list');
    }

    /*public function setImageLinkArray($file=false)
    {
        $helper = $this->getHelper();

        if (!$file){
            $file = $this->getFileCsv();
        } else
        if ($file['size'] > 0) {
            if (file_exists($file['tmp_name'])) {
                $handle = fopen($file['tmp_name'], "r");
                if ($handle !== false) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                       $this->imageLinkArray[] = $row;
                    }
                    fclose($handle);
                } else {
                    Mage::throwException($helper->__('Error when opening file!'));
                }
             } else {
                 Mage::throwException($helper->__('The file does not exist. Try again!'));
             }
         } else {
             Mage::throwException($helper->__('Add file in CSV format!'));
         }
         return $this;
    }

    public function saveImageLinks()
    {
        $prepareInsertArray = $this->getPrepareInsertArray();
        if ( count($prepareInsertArray) > 0 ){
            $this->getResource()->insertMultiple($prepareInsertArray);
        } else {
            Mage::throwException($this->getHelper()->__('File is empty!'));
        }
    }*/

    public function saveImageLinks($is_validate=true)
    {
        $valid = true;
        if ($is_validate) {
            $valid = $this->validateImageLinks();
        }
        if ($valid) {
            $this->prepareImageLinks();
            $this->getResource()->insertMultiple($this->prepareInsertArray);
        } else {
            $errorMessage = $this->getErrorMessage();
            Mage::throwException($errorMessage);
        }
    }

    public function validateImageLinks()
    {
        $valid = $this->validFileCsv();
        if (!$valid) {
            return false;
        }
        return true;
    }

    protected function validFileCsv()
    {
        $helper = $this->getHelper();
        $file = $this->getFileCsv();
        if (isset($file) && $file['size'] > 0) {
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
    }

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

    protected function setPrepareInsertArray()
    {
        $gmtdate = Mage::getModel('core/date')->gmtdate();
        foreach ($this->imageLinkArray as $value) {
            if (is_null($value[0])){
                continue;
            }
            $this->prepareInsertArray[] = array(
                'sku'               =>  $value[0],
                'create_datetime'   =>  $gmtdate,
                'img_url'           =>  $value[1]
            );
        }
    }

    protected function getErrorMessage()
    {
        $msg = '';
        for ($i=0;$i<count($this->errors);$i++) {
            $n = $i + 1;
            $msg .= $n.' '.$this->errors[$i].'<br>';
        }
        return $msg;
    }

    protected function getHelper()
    {
        return Mage::helper('zhupanyn_imgloader');
    }
}