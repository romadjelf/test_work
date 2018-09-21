<?php

class Zhupanyn_Imgloader_Block_Adminhtml_Imgloader_Edit_Tab_List extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setEmptyText($this->helper('zhupanyn_imgloader')->__('No uploaded images'));
        $this->setId('zhupanyn_imgloader_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSkipGenerateContent(true);
        $this->setUseAjax(true);
    }

    public function getTabLabel()
    {
        return $this->helper('zhupanyn_imgloader')->__('Images import');
    }

    public function getTabTitle()
    {
        return $this->helper('zhupanyn_imgloader')->__('Table with image loading statuses');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getTabUrl()
    {
        return $this->getUrl('adminhtml/zhupanyn_imgloader/list', array('_current'=>true));
    }

    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * Retrieve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }

    protected function _prepareCollection()
    {
        /* @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract*/
        $collection = Mage::getModel('zhupanyn_imgloader/list')->getCollection();
        $collection->addFieldToFilter('sku', array('eq'=>$this->_getProduct()->getSku()));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = $this->helper('zhupanyn_imgloader');
        $this->addColumn('id', array(
            'header'    => $helper->__('ID'),
            'align'     => 'center',
            'width'     => '50px',
            'index'     => 'id'
        ));

        $this->addColumn('create_datetime', array(
            'header' => $helper->__('Create Datetime'),
            'index'  => 'create_datetime',
            'type'   => 'datetime',
            'filter_time' => true
        ));

        $this->addColumn('update_datetime', array(
            'header' => $helper->__('Update Datetime'),
            'index'  => 'update_datetime',
            'type'   => 'datetime',
            'filter_time' => true
        ));

        $this->addColumn('img_url', array(
            'header' => $helper->__('Image Url'),
            'align'  => 'left',
            'type'   => 'wrapline',
            'index'  => 'img_url',
            'lineLength' => 40
        ));

        $this->addColumn('img_size', array(
            'header' => $helper->__('Image Size'),
            'align'  => 'center',
            'type'   => 'text',
            'index'  => 'img_size',
            'frame_callback' => array($this, 'renderImgSize')
        ));

        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'align'  => 'center',
            'type'   => 'options',
            'index'  => 'status',
            'options'=> Zhupanyn_Imgloader_Model_List::getStatusArray()
        ));

        $this->addColumn('error_text', array(
            'header' => $helper->__('Error Text'),
            'align'  => 'left',
            'type'   => 'text',
            'index'  => 'error_text'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Get Grid Url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->_getData('grid_url')
            ? $this->_getData('grid_url') : $this->getUrl('adminhtml/zhupanyn_imgloader/listgrid', array('_current'=>true));
    }

    /**
     * Вывод размера картинки в МБ или КБ
     *
     * @param string $renderedValue
     * @param Varien_Object $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param boolean $isExport
     * @return string
     */
    public function renderImgSize($renderedValue, $row, $column, $isExport)
    {
        $bytes = $row->getImgSize();
        if (!empty($bytes)) {
            $KBytes = $this->getKBytes($bytes);
            if ($KBytes > 1024) {
                $MBytes = $this->getMBytes($bytes);
                $renderedValue = $MBytes.'мб';
            } else {
                $renderedValue = $KBytes.'кб';
            }
        }
        return $renderedValue;
    }

    /**
     * Получить КБ из байтов
     *
     * @param integer $bytes
     * @return integer
     */
    protected function getKBytes($bytes)
    {
        return round(intval($bytes)/1024, 0);
    }

    /**
     * Получить Мб из байтов
     *
     * @param integer $bytes
     * @return float
     */
    protected function getMBytes($bytes)
    {
        return round(intval($bytes)/1024/1024, 2);
    }
}
