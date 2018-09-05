<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 28.08.18
 * Time: 12:06
 */

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
        /*if ($this->_getProduct()->getId()) {
            $this->setDefaultFilter(array('sku'=>$this->_getProduct()->getSku()));
        }*/
    }

    public function getTabLabel()
    {
        return $this->helper('zhupanyn_imgloader')->__('Импорт изображений');
    }

    public function getTabTitle()
    {
        return $this->helper('zhupanyn_imgloader')->__('Таблица с статусами загрузки картинок');
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

    /*protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            else {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }*/

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

        /*$this->addColumn('sku', array(
            'header' => $helper->__('Sku'),
            'align'  => 'left',
            'type'   => 'text',
            'index'  => 'sku'
        ));*/

        $this->addColumn('create_datetime', array(
            'header' => $helper->__('Create Datetime'),
            'index'  => 'create_datetime',
            'type'   => 'datetime',
            'filter_time' => true,
            //'width' => '150px'
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
            'align'  => 'left',
            'type'   => 'text',
            'index'  => 'img_size'
        ));

        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'align'  => 'center',
            'type'   => 'options',
            'index'  => 'status',
            //'filter' => 'adminhtml/widget_grid_column_filter_select',
            'options'=> $helper->getStatusArray()
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
}
