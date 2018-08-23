<?php
class Zhupanyn_Action_Adminhtml_Zhupanyn_ActionController extends Mage_Adminhtml_Controller_Action
{
   public function indexAction()
   {
      //$link = Mage::getUrl('adminhtml/main');
     // echo 'нажать тут <a href="'.$link.'">выход</a>';
      $this->loadLayout();
      $this->renderLayout();
   }

   public function newAction()
   {
      $this->_forward('edit');
   }

   public function editAction()
   {
      $id = (int)$this->getRequest()->getParam('id');
      Mage::register('current_action', Mage::getModel('zhupanyn_action/action')->load($id));
      $this->loadLayout()->_setActiveMenu('main_action');
      $this->_addContent($this->getLayout()->createBlock('zhupanyn_action/adminhtml_action_edit'));
      $this->renderLayout();
   }

   public function saveAction()
   {
      if ($data = $this->getRequest()->getPost()) {
         try {
            $model = Mage::getModel('dsnews/news');
            $model->setData($data)->setId($this->getRequest()->getParam('id'));
            if(!$model->getCreated()){
               $model->setCreated(now());
            }
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('News was saved successfully'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/*/');
         } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            $this->_redirect('*/*/edit', array(
               'id' => $this->getRequest()->getParam('id')
            ));
         }
         return;
      }
      Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
      $this->_redirect('*/*/');
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
}
?>