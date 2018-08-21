<?php
class Zhupanyn_Action_IndexController extends Mage_Core_Controller_Front_Action
{
   public function indexAction()
   {
      $this->loadLayout();

      $actions = Mage::getModel('zhupanyn_action/action_product');
      $t = 'Класс: '.get_class($actions);

      $block = $this->getLayout()->createBlock('core/text','my_block_roma',array('text'=>$t));
      $this->getLayout()->getBlock('content')->append($block);

      $this->renderLayout();
   }

   public function collectionAction()
   {
      $actions = Mage::getModel('zhupanyn_action/action')->getCollection();
      echo 'Класс Коллекции '.get_class($actions).'<br><br>';
      foreach ($actions as $key=>$action )
      {
         echo 'Строчка: '.$key;
         echo '<br>Name: '.$action->name;
         echo '<br>Description: '.$action->description;
         echo '<br>Активность: '.$action->is_active;
         echo '<hr><br>';
      }
      $a = Mage::getModel('zhupanyn_action/action')->getResource();
      $b = new Zhupanyn_Action_Model_Resource_Action_Collection();


      //$this->loadLayout();
      //$this->renderLayout();
   }

   public function resourceAction()
   {
      $action = Mage::getModel('zhupanyn_action/action');
      $r = $action->getResource();
      echo 'Класс '.get_class($action);
      echo '<br>Resource '.get_class($r);
      $action->load(1);
      $data = $action->getData();
      echo '<br>';
      echo '<pre>';
      var_dump($data);
      echo '</pre>';

      //$action->setIsActive(2);
      //$action->setName('Первый замена');
      //$action->setDescription('Первая строчка замена');
      $action->setData(array('is_active'=>1,'name'=>'Четвертая У','description'=>'Четвертая У строчка'));
      $action->save();
      echo '<pre>';
      var_dump($action->getData());
      echo '</pre>';

      //$this->loadLayout();
      //$this->renderLayout();
   }

   public function modelAction()
   {
      $action = Mage::getModel('zhupanyn_action/action',
      array('is_enabled' => 'No',
            'group_id'   => 1,
            'name'       => 'Вася Пупкин'));
      echo 'Тут '.get_class($action);

      echo '<br>'.$action->EvenPrefix();

      echo '<br>E1: '.$action->getIsEnabled();
      $action->is_enabled = 'Yes';
      echo '<br>E2: '.$action['is_enabled'].' <br>';

      echo '<pre>';
      print_r($action->getData());
      echo '</pre>';

      $action->setData('friends', array(
        'university' => array(1,2,3,4,5),
        'home'       => array('Petrov' => 1, 'Pupkin' => 2)
      ));

      echo '<pre>';
      print_r($action->getData());
      echo '</pre>';

      echo $action->getData('friends/university/4');

      //$this->loadLayout();
      //$this->renderLayout();
   }
}
?>
