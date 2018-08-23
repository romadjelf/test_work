<?php
class Zhupanyn_Action_Block_Adminhtml_Action_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
   protected function _construct()
   {
      $this->_blockGroup = 'zhupanyn_action';
      $this->_controller = 'adminhtml_action';
   }

   public function getHeaderText()
   {
      $helper = Mage::helper('zhupanyn_action');
      $model = Mage::registry('current_action');
      if ($model->getId()) {
         return $helper->__("Редагування акції '%s'", $this->escapeHtml($model->getName()));
      } else {
         return $helper->__("Додавання акції");
      }
   }
}
?>
