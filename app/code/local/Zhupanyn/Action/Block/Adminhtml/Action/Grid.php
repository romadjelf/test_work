<?php
class Zhupanyn_Action_Block_Adminhtml_Action_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
   public function __construct()
   {
      parent::__construct();
      $this->setEmptyText('Нет записей');
      $this->setId('zhupanyn_action_table');
      $this->setDefaultLimit(10);
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
//      $this->setSaveParametersInSession(true);

   }

   protected function _prepareCollection()
   {
      $collection = Mage::getModel('zhupanyn_action/action')->getCollection();
      $collection->setPageSize(5);
      $this->setCollection($collection);
      return parent::_prepareCollection();
   }

   protected function _prepareColumns()
   {
      $helper = Mage::helper('zhupanyn_action');
      $this->addColumn('id', array(
         'header'    => $helper->__('ID'),
         'align'     => 'center',
         'width'     => '100px',
         'index'     => 'id',
      ));

      //$t = new Mage_Adminhtml_Block_Widget_Grid_Column();
      //$t = new Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select();
      //$t->

      $this->addColumn('is_active', array(
         'header'    => $helper->__('Is Active'),
         'align'     => 'center',
         'width'     => '100px',
         'type'      => 'options',
         'index'     => 'is_active',
         //'filter'    => 'adminhtml/widget_grid_column_filter_select',
         'options'   => $helper->getIsActiveArray()
      ));

      $this->addColumn('name', array(
         'header' => $helper->__('Name'),
         'align'  => 'left',
         'type'   => 'text',
         'index'  => 'name'
      ));

      $this->addColumn('short_description', array(
         'header' => $helper->__('Short Description'),
         'align'  => 'left',
         'type'   => 'text',
         'index'  => 'short_description'
      ));

      $this->addColumn('create_datetime', array(
         'header' => $helper->__('Create Datetime'),
         'index'  => 'create_datetime',
         'type'   => 'datetime'
      ));

      $this->addColumn('start_datetime', array(
         'header' => $helper->__('Start Datetime'),
         'index'  => 'start_datetime',
         'type'   => 'datetime'
      ));

      $this->addColumn('end_datetime', array(
         'header' => $helper->__('End Datetime'),
         'index'  => 'end_datetime',
         'type'   => 'datetime'
      ));

      $this->addColumn('status', array(
         'header' => $helper->__('Status'),
         'align'  => 'center',
         'type'   => 'options',
         'index'  => 'status',
         //'filter' => 'adminhtml/widget_grid_column_filter_select',
         'options'=> $helper->getStatusArray()
      ));

      return parent::_prepareColumns();
   }

   protected function _prepareMassaction()
   {
      $helper = Mage::helper('zhupanyn_action');
      $this->setMassactionIdField('id');
      $this->getMassactionBlock()->setFormFieldName('id_mass');
      $this->getMassactionBlock()->addItem('delete', array(
         'label' => $this->__('Delete'),
         'url' => $this->getUrl('*/*/massDelete'),
      ));

      $is_active_value = $helper->getIsActiveArray();

      $this->getMassactionBlock()->addItem('status', array(
         'label' => $helper->__('Change Active'),
         'url' => $this->getUrl('*/*/massStatus', array('_current'=>true)),
         'additional' => array(
              'act' => array(
                   'name' => 'is_active',
                   'type' => 'select',
                   //'class' => 'required-entry',
                   'label' => $helper->__('Is Active'),
                   'values' => $is_active_value,
               )
         )
      ));

      return $this;
   }

   public function getRowUrl($row)
   {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
   }
}
?>
