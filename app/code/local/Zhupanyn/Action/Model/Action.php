<?php
class Zhupanyn_Action_Model_Action extends Mage_Core_Model_Abstract {

   public function _construct()
   {
      $this->_init('zhupanyn_action/action');
   }

   public function EvenPrefix()
   {
      return $this->_eventPrefix;
   }

   public function uploadImage($fieldName, $helper)
   {
      $file = $_FILES[$fieldName];
      if( isset($file) ) {
         $imageFile = $this->getData($fieldName);
         if( file_exists($file['tmp_name']) ){
            if( $imageFile ){
               unlink( $helper->getImagePath($imageFile) );
            }
            try
            {
               $uploader = new Varien_File_Uploader($file);
               $uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
               $uploader->setAllowRenameFiles(true);
               $uploader->setFilesDispersion(false);
               $uploader->save($helper->getImagePath(), $file['name']);
               $this->setData($fieldName,$uploader->getUploadedFileName());
            }
            catch(Exception $e)
            {
               Mage::throwException('Помилка при завантаженні картинки!<br>'.$e->message);
            }
         } elseif( is_null($imageFile) ){
            Mage::throwException('Додайте картинку!');
         }
      }
      return $this;
   }

   public function deleteImage($fieldName, $helper, $image)
   {
      if ( !is_null($image) && isset($image['delete']) && $image['delete'] == 1 ){
         unlink( $helper->getImagePath($this->getData($fieldName)) );
         $this->setData($fieldName, null);
      }
      return $this;
   }
}
?>
