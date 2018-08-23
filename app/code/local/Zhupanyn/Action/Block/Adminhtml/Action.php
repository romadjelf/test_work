<?php
class Zhupanyn_Action_Block_Adminhtml_Action extends Mage_Adminhtml_Block_Widget_Grid_Container
{
   protected function _construct()
   {
      parent::_construct();
      $this->_blockGroup = 'zhupanyn_action';
      $this->_controller = 'adminhtml_action';
      $this->_headerText = Mage::helper('zhupanyn_action/data')->__('Управление Акциями');
      $this->_addButtonLabel = Mage::helper('zhupanyn_action/data')->__('Add Action');
   }
}
?>
