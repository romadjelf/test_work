<?php
class Zhupanyn_Newproducts_Model_Observer
{
   public function zh_add_menu_node(Varien_Event_Observer  $observer)
   {

      $menu = $observer->getMenu();
      $block = $observer->getBlock();

      $added_node = new Varien_Data_Tree_Node(
         array(
            'name'=>'Zh_List',
            'id'=>'my_list',
            'url'=>Mage::getUrl('zh_newproducts/index/list')
         ),'id',$menu->getTree(),$menu);

      $menu->addChild($added_node);

      return $this;
   }
}

