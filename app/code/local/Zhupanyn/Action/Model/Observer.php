<?php
class Zhupanyn_Action_Model_Observer
{
   public function add_menu_node(Varien_Event_Observer  $observer)
   {
      $menu = $observer->getMenu();
      //$block = $observer->getBlock();

      $added_node = new Varien_Data_Tree_Node(
         array(
            'name'=>'Акції',
            'id'=>'my_action',
            'url'=>Mage::getUrl('zhupanyn_action/index/index')
         ),'id',$menu->getTree(),$menu);

      $menu->addChild($added_node);

      return $this;
   }
}
?>