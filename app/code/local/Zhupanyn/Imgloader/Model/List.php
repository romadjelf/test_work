<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 27.08.18
 * Time: 15:27
 */

class Zhupanyn_Imgloader_Model_List extends Mage_Core_Model_Abstract
{
    protected $imageLinkArray = [];

    public function _construct()
    {
        $this->_init('zhupanyn_imgloader/list');
    }

    public function setImageLinkArray($file)
    {
        $helper = $this->getHelper();
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
    }

    protected function getPrepareInsertArray()
    {
        $prepareInsertArray = [];
        $gmtdate = Mage::getModel('core/date')->gmtdate();
        foreach ($this->imageLinkArray as $value) {
            if (is_null($value[0])){
                continue;
            }
            $prepareInsertArray[] = array(
                'sku'               =>  $value[0],
                'create_datetime'   =>  $gmtdate,
                'img_url'           =>  $value[1]
            );
        }
        return $prepareInsertArray;
    }

    protected function getHelper()
    {
        return Mage::helper('zhupanyn_imgloader');
    }
}