<?php
class Zhupanyn_Action_Adminhtml_Zhupanyn_ActionController extends Mage_Adminhtml_Controller_Action
{
   public function indexAction()
   {
      //$link = Mage::getUrl('adminhtml/main');
     // echo 'нажать тут <a href="'.$link.'">выход</a>';
      $this->loadLayout()->_setActiveMenu('main_action');
      $this->renderLayout();
   }

   public function newAction()
   {
      $this->_forward('edit');
   }

   public function editAction()
   {
      $id = (int)$this->getRequest()->getParam('id');
      $model = Mage::getModel('zhupanyn_action/action')->load($id);
      Mage::register('current_action', $model);

      $formdata = Mage::getSingleton('adminhtml/session')->getZhActionFormData();
      if(count($formdata)) {
         Mage::registry('current_action')->addData($formdata);
         Mage::getSingleton('adminhtml/session')->setZhActionFormData(array());
      }

      $this->loadLayout()->_setActiveMenu('main_action');
      $this->_addContent($this->getLayout()->createBlock('zhupanyn_action/adminhtml_action_edit'));
      $this->renderLayout();
   }

   public function saveAction()
   {
      try {
         $id = $this->getRequest()->getParam('id');
         $post_data = $this->getRequest()->getPost();
         $helper = Mage::helper('zhupanyn_action');

         $delete_image = null;
         if ( isset($post_data['image']) ){
            $delete_image = $post_data['image'];
            unset($post_data['image']);
         }

         $model = Mage::getModel('zhupanyn_action/action')->load($id);
         $model->addData($post_data);
         $model->deleteImage('image', $helper, $delete_image);
         $model->uploadImage('image', $helper);
         $model->setCreateDatetime( $helper->getDatetimeGmt($model->create_datetime, true) );
         $model->setStartDatetime( $helper->getDatetimeGmt($model->start_datetime) );
         $model->setEndDatetime( $helper->getDatetimeGmt($model->end_datetime) );
         $model->save();

         $this->_getSession()->addSuccess($this->__('News was saved successfully'));
         Mage::getSingleton('adminhtml/session')->setZhActionFormData(array());
         //$this->_redirect('*/*/');
         $this->_redirect('*/*/'.$this->getRequest()->getParam('back','index'),array('id'=>$model->getId()));
      } catch (Exception $e) {
         $this->_getSession()->addError($e->getMessage());
         Mage::getSingleton('adminhtml/session')->setZhActionFormData($model->getData());
         $this->_redirect('*/*/edit', array(
            'id' => $this->getRequest()->getParam('id')
         ));
      }

// сохранение с проверкой поста и запись полученого поста в пустую модель, без считывания есть ли уже существующая модель
//      if ($data = $this->getRequest()->getPost()) {
//         try {
//            $model = Mage::getModel('zhupanyn_action/action');
//            $id = $this->getRequest()->getParam('id');
//            $model->setData($data)->setId($id);
//            $datanow = Mage::getModel('core/date')->gmtdate();
//            if(!$model->getCreateDatetime()){
//               $model->setCreateDatetime($datanow);
//            }
//            $model->start_datetime = (isset($model->start_datetime)) ? Mage::getModel('core/date')->gmtdate(null,$model->start_datetime):null;
//            $model->end_datetime = (empty($model->end_datetime)) ? null : Mage::getModel('core/date')->gmtdate(null,$model->end_datetime);
//            $model->save();
//            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was saved successfully'));
//            Mage::getSingleton('adminhtml/session')->setZhActionFormData(false);
//            $this->_redirect('*/*/');
//         } catch (Exception $e) {
//            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//            Mage::getSingleton('adminhtml/session')->setZhActionFormData($data);
//            $this->_redirect('*/*/edit', array(
//               'id' => $this->getRequest()->getParam('id')
//            ));
//         }
//         return;
//      }
//      Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
//      $this->_redirect('*/*/');
   }

   public function massDeleteAction()
   {
      $actions = $this->getRequest()->getParam('id_mass', null);
      if (is_array($actions) && sizeof($actions) > 0) {
         try {
            foreach ($actions as $id) {
               Mage::getModel('zhupanyn_action/action')->setId($id)->delete();
            }
            $this->_getSession()->addSuccess($this->__('Акції в кількості %d шт. були видалені.', sizeof($actions)));
         } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
         }
      } else {
         $this->_getSession()->addError($this->__('Виберіть хоча б одну акцію.'));
      }
      $this->_redirect('*/*');
   }

   public function massStatusAction()
   {
      $params = $this->getRequest()->getParams();
      try {
         $actions = Mage::getModel('zhupanyn_action/action')->getCollection()
             ->addFieldToFilter('id',array('in'=>$params['id_mass']));
         foreach($actions as $action) {
             $action->setIsActive($params['is_active'])->save();
         }
         $this->_getSession()->addSuccess('Акции обновлены!');
      } catch(Exception $e) {
         Mage::logException($e);
         $this->_getSession()->addError($e->getMessage());
      }
      return $this->_redirect('*/*/');
   }

   protected function _uploadImage($fieldName, $model)
   {
      $file = $_FILES[$fieldName];
      if( isset($file) ) {

         if( file_exists($file['tmp_name']) ){
            if( $model->getId() ){
               unlink( $helper->getImagePath($model->getData($fieldName)) );
            }
            try
            {
               $uploader = new Varien_File_Uploader($file);
               $uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
               $uploader->setAllowRenameFiles(true);
               $uploader->setFilesDispersion(false);
               $uploader->save($helper->getImagePath(), $file['name']);
               $model->setData($fieldName,$uploader->getUploadedFileName());
               return true;
            }
            catch(Exception $e)
            {
                return false;
            }
         }
      } else {
         return false;
      }
   }
}
?>