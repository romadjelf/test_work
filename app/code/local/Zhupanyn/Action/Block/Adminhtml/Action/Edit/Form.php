<?php
class Zhupanyn_Action_Block_Adminhtml_Action_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

   public function __construct()
   {
      parent::__construct();
      //$this->setId('zh_block_form');
      //$this->setTitle(Mage::helper('zhupanyn_action')->__('Блок формы'));
   }

   protected function _prepareForm()
   {
      $helper = Mage::helper('zhupanyn_action');
      $model = Mage::registry('current_action');
      $form = new Varien_Data_Form(array(
         'id' => 'zh_edit_form',
         'action' => $this->getUrl('*/*/save', array(
            'id' => $this->getRequest()->getParam('id')
         )),
         'method' => 'post',
         'enctype' => 'multipart/form-data'
      ));

      $model->create_datetime = Mage::getModel('core/date')->date(null,$model->create_datetime);
      $model->start_datetime = Mage::getModel('core/date')->date(null,$model->start_datetime);
      $model->end_datetime = Mage::getModel('core/date')->date(null,$model->end_datetime);
      $model->status = $helper->getStatusArray()[$model->status];

      $form->setHtmlIdPrefix('zh_');

      //$this->setForm($form);
      $fieldset = $form->addFieldset('action_form', array(
         'legend' => $helper->__('Інформація про акцію')
      ));

      $fieldset->addField('name', 'text', array(
         'label'     => $helper->__('Ім\'я'),
         'class'     => 'required-entry',
         'required'  => true,
         'name'      => 'title',
         //'onclick' => "alert('on click');",
         //'onchange' => "alert('on change');",
         //'style'   => "border:10px",
         //'value'  => $model->getName(),//'hello !!',
         'disabled' => false,
         //'readonly' => true,
         'after_element_html' => '<small>Назва акції</small>',
         'tabindex' => 1
      ));
      $fieldset->addField('is_active', 'select', array(
         'label'     => $helper->__('Активний'),
         'class'     => 'required-entry',
         'required'  => true,
         'name'      => 'title',
         //'onclick' => "",
         //'onchange' => "",
         //'value'  => $model->getIsActive(),
         'values' => $helper->getIsActiveArray(),//array('-1'=>'Please Select..','1' => 'Option1','2' => 'Option2', '3' => 'Option3'),
         'disabled' => false,
         //'readonly' => false,
         'after_element_html' => '<small>Активний стан</small>',
         'tabindex' => 2
      ));

      $fieldset->addField('description', 'textarea', array(
         'label'     => $helper->__('Опис'),
         'class'     => 'required-entry',
         'required'  => false,
         'name'      => 'title',
         //'onclick' => "",
         //'onchange' => "",
         //'value'  => $model->getDescription(),
         'disabled' => false,
         //'readonly' => false,
         'after_element_html' => '<small>Опис акції</small>',
         'tabindex' => 3,
         'style'     => 'height:5em;'
      ));

      $fieldset->addField('short_description', 'textarea', array(
         'label'     => $helper->__('Короткий опис'),
         'class'     => 'required-entry',
         'required'  => false,
         'name'      => 'short_description',
         //'onclick' => "",
         //'onchange' => "",
         //'value'  => "",
         'disabled'  => true,
         //'readonly' => false,
         'after_element_html' => '<small>Короткий опис акції</small>',
         'tabindex'  => 4,
         'style'     => 'height:5em;'
      ));

      $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
         Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
      );
      $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
         Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
      );

      $fieldset->addField('create_datetime', 'date', array(
         'name'      => 'create_datetime',
         'label'     => $helper->__('Дата створення'),
         'image'     => $this->getSkinUrl('images/grid-cal.gif'),
         'format'    => $dateFormatIso,
         //'disabled'  => $isElementDisabled,
         'class'     => 'validate-date validate-date-range date-range-custom_theme-from',
         'tabindex'  => 5
      ));

      $fieldset->addField('start_datetime', 'time', array(
         'name'      => 'start_datetime',
         'label'     => $helper->__('Дата початку акції'),
         'image'     => $this->getSkinUrl('images/grid-cal.gif'),
         'format'    => $dateFormatIso,
         //'disabled'  => $isElementDisabled,
         //'class'     => 'validate-date validate-date-range date-range-custom_theme-from',
         'tabindex'  => 6
      ));

      $fieldset->addField('end_datetime', 'time', array(
         'name'      => 'end_datetime',
         'label'     => $helper->__('Дата початку акції'),
         'image'     => $this->getSkinUrl('images/grid-cal.gif'),
         'format'    => $dateFormatIso,
         //'disabled'  => $isElementDisabled,
         //'class'     => 'validate-date validate-date-range date-range-custom_theme-from',
         'tabindex'  => 7
      ));

      $fieldset->addField('status', 'label', array(
         'label'  => $helper->__('Статус'),
         'value'  => $helper->getStatusArray(),
         'bold'   => true,
         'note'   => 'Значення статуса змінюється через крон'
      ));

      //return array('type', 'title', 'class', 'style', 'onclick', 'onchange', 'disabled', 'readonly', 'tabindex');

//      $fieldset->addField('title', 'text', array(
//         'label' => $helper->__('Title'),
//         'required' => true,
//         'name' => 'title',
//      ));
//      $fieldset->addField('content', 'editor', array(
//         'label' => $helper->__('Content'),
//         'required' => true,
//         'name' => 'content',
//      ));
//      $fieldset->addField('created', 'date', array(
//         'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
//         'image' => $this->getSkinUrl('images/grid-cal.gif'),
//         'label' => $helper->__('Created'),
//         'name' => 'created'
//      ));

//      if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
//         $form->setValues($data);
//      } else {
//         $form->setValues($model->getData());
//      }
      $form->setValues($model->getData());
      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
   }

}
?>
