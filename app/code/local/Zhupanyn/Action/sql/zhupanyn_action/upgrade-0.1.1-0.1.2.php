<?php

$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('zhupanyn_action/action_product');
$refActionTable = $installer->getTable('zhupanyn_action/action');
$refProductTable = $installer->getTable('catalog/product');

//$a = new Varien_Db_Ddl_Table();
//$a->addIndex('action_product_unique', array('action_id','product_id'), array('type'=>Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE));
//$a->addForeignKey(FK_ACTION,'action_id',$refActionTable,'id',
//Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE);

$indexName = $installer->getConnection()->getIndexName(
    $tableName,
    array('action_id','product_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$table = $installer->getConnection()->newTable($tableName)
   ->addColumn('id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,
      array(
         'identity' => true,
         'unsigned' => true,
         'nullable' => false,
         'primary' => true ),
      'ID of row')
   ->addColumn('action_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
      array(
         'unsigned' => true,
         'nullable' => false ),
      'Action Id')
   ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
      array(
         'unsigned' => true,
         'nullable' => false ),
      'Product Id')
   ->addForeignKey('FK_ACTION', 'action_id', $refActionTable, 'id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
   ->addForeignKey('FK_PRODUCT', 'product_id', $refProductTable, 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$table->addIndex($indexName,
      array(
         'action_id',
         'product_id'),
      array(
         'type'=>Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE));

$installer->getConnection()->createTable($table);

$installer->endSetup();

?>
