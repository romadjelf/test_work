<?php

$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('zhupanyn_action/action');

//$a = new Varien_Db_Adapter_Pdo_Mysql();
//$a->addColumn();
$installer->getConnection()
   ->addColumn($tableName,'status',
      array(
         'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
         'nullable' => false,
         'default' => 1,
         'comment' => 'Status: 1 - час дії ще ненаступив, 2 - акція діє, 3 - акція закрита')
   );

$installer->endSetup();
?>
