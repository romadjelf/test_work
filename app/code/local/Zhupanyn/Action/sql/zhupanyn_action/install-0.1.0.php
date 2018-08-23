<?php

// Загальний стандарт Magento. Створюємо змінну $installer.
$installer = $this;

// Обов"язкова конструкція на початку інсталяції
$installer->startSetup();

$tableName = $installer->getTable('zhupanyn_action/action');

// Генеруємо назву індексу
/*$indexName = $installer->getConnection()->getIndexName(
    $tableName,
    array('url_path'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);*/

//$installer->getConnection()->dropTable($tableName);

// Створюємо обе"єкт таблиці.
// addColumn функция Varien_Db_Ddl_Table
//$a = new Varien_Db_Ddl_Table();

$table = $installer->getConnection()->newTable($tableName)
   ->addColumn('id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,
      array(
         'identity' => true,
         'unsigned' => true,
         'nullable' => false,
         'primary' => true),
      'ID of row')
   ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null,
      array(
         'nullable' => false),
      'Is Active')
   ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
      array(
         'nullable' => false),
      'Name')
   ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null,
      array(),
      'Description')
   ->addColumn('short_description', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
      array(),
      'Short Description')
   ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
      array(),
      'Image')
   ->addColumn('create_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
      array(
         'nullable' => false),
      'Create Datetime')
   ->addColumn('start_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
      array(
         'nullable' => false),
      'Start Datetime')
   ->addColumn('end_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
      array(
         'nullable' => false),
      'End Datetime');

// Додаємо індекс
/*$table->addIndex(
    $indexName,
    array('url_path'),
    array(
        'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    )
);*/

// Генеруємо таблицю в базі
$installer->getConnection()->createTable($table);

//$installer->run("");

// Обов"язкова конструкція в кінці інсталяції
$installer->endSetup();

?>
