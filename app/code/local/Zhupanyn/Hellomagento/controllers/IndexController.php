<?php
class Zhupanyn_Hellomagento_IndexController extends Mage_Core_Controller_Front_Action
{
   public function indexAction()
   {
      echo 'Hello Magento!';
   }

   public function listAction()
   {
      echo 'Hello Magento List!';
   }

   public function contAction()
   {
      $this->loadLayout();

      $block = $this->getLayout()->createBlock('core/text','my_block_roma',array('text'=>'Hello Inna!'));
      $this->getLayout()->getBlock('content')->append($block);

      $this->renderLayout();
   }
}
?>
