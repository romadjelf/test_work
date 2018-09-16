<?php

class Zhupanyn_Imgloader_Block_Adminhtml_Button extends Mage_Core_Block_Abstract
{
    protected function _prepareLayout()
    {
        $productsList = $this->getLayout()->getBlock('products_list');
        if ( $productsList ) {
            $url = Mage::helper('adminhtml')->getUrl('adminhtml/zhupanyn_imgloader/new');
            $productsList->addButton('zh_import', array(
                'label'     => $this->helper('zhupanyn_imgloader')->__('Импорт изображений'),
                'onclick'   => "setLocation('{$url}')",
                'class'     => 'add'
            ));

            $url = Mage::helper('adminhtml')->getUrl('adminhtml/zhupanyn_imgloader/index');
            $productsList->addButton('zh_index', array(
                'label'     => $this->helper('zhupanyn_imgloader')->__('Index'),
                'onclick'   => "setLocation('{$url}')"
            ));
        }
    }
}