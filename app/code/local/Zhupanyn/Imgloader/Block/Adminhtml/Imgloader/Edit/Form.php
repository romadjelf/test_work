<?php

class Zhupanyn_Imgloader_Block_Adminhtml_Imgloader_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareForm()
    {
        $helper = $this->helper('zhupanyn_imgloader');
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setHtmlIdPrefix('zh_');

        $fieldset = $form->addFieldset('action_form', array(
            'legend' => $helper->__('Import CSV File')
        ));

        $fieldset->addField('file', 'file', array(
            'label'     => $helper->__('Upload file *.csv'),
            'value'     => 'Uplaod',
            'disabled'  => false,
            'readonly'  => true,
            'after_element_html' => '<small>add file with image list in csv format</small>',
            'tabindex'  => 1,
            'name'      => 'file'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}