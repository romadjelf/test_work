<?php
class Zhupanyn_Action_Helper_Data extends Mage_Core_Helper_Abstract
{

   public function getIsActiveArray()
   {
      return array(
         0=>$this->__('No'),
         1=>$this->__('Yes')
      );
   }

   public function getIsActiveFormArray()
   {
      return array('-1'=>$this->__('-- Виберіть значення --')) + $this->getIsActiveArray();
   }

   public function getStatusArray()
   {
      return array(
         1=>$this->__('Off'),
         2=>$this->__('On'),
         3=>$this->__('Close')
      );
   }

   public function getDatetime($datetime, $format=null, $empty_value='')
   {
      if ( isset($datetime) ) {
         if ( is_null($format) ){
            $datetime = Mage::getModel('core/date')->date(null,$datetime);
         } else {
            $datetime = Mage::app()->getLocale()->date($datetime)->toString($format);
         }
      } else {
         $datetime = $empty_value;
      }
      return $datetime;
   }

   public function getDatetimeGmt($datetime, $is_create_datetime=false)
   {
      if ( isset($datetime) ) {
         if (!$is_create_datetime){
            //$datetime = Mage::getModel('core/date')->gmtdate(null,$datetime);
            $datetime = Mage::app()->getLocale()->date($datetime,null,null,false)->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
         }
      } else {
         if (!$is_create_datetime){
            $datetime = null;
         } else {
            //$datetime = Mage::getModel('core/date')->gmtdate();
            $datetime = Mage::app()->getLocale()->date($datetime,null,null,false)->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
         }
      }
      return $datetime;
   }

   public function getImage($image)
   {
      if ( isset($image) ){
         if ( isset($image['value']) ){
            $image = $image['value'];
         } else {
            $image = 'zhupanyn/action/'.$image;
         }
      }
      return $image;
   }

   public function getImagePath($file_name = '')
   {
      $path = Mage::getBaseDir('media').DS.'zhupanyn'.DS.'action';
      if ($file_name) {
         return $path.DS.$file_name;
      } else {
         return $path;
      }
   }

   public function getImageUrl($file_name)
   {
      $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'zhupanyn/action/';
      if ($file_name) {
         return $url.$file_name;
      } else {
         return $url;
      }
   }

}
?>
