<?php

class Zhupanyn_Imgloader_Block_Adminhtml_Imgloader_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'zhupanyn_imgloader';
        $this->_controller = 'adminhtml_imgloader';
    }

    public function getHeaderText()
    {
        return $this->helper('zhupanyn_imgloader')->__('Import Image List');
    }
}