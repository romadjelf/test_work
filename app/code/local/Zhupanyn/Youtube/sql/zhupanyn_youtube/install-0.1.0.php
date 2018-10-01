<?php

/* @var $installer Zhupanyn_Youtube_Model_Resource_Setup*/
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('zhupanyn_youtube/youtube');

// Генеруємо назву індексу
/*$indexName = $installer->getConnection()->getIndexName(
    $tableName,
    array('url_path'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);*/

$installer->getConnection()->dropTable($tableName);

// Створюємо обе"єкт таблиці.
// addColumn функция Varien_Db_Ddl_Table
//$a = new Varien_Db_Ddl_Table();

$table = $installer->getConnection()->newTable($tableName)
   ->addColumn('id_product',Varien_Db_Ddl_Table::TYPE_INTEGER,null,
      array(
         'unsigned' => true,
         'nullable' => false,
         'primary' => true),
      'ID of Product')
    ->addColumn('id_youtube', Varien_Db_Ddl_Table::TYPE_TEXT, 64,
        array(
            'nullable' => false),
        'Id Youtube')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 128,
        array(
            'nullable' => false),
        'Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 5000,
        array(),
        'Description')
    ->addColumn('url_thumbnail', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false),
        'Url Thumbnail')
    ->addColumn('publishedAt', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
        array(
            'nullable' => false),
        'Published At');

// Додаємо індекс
/*$table->addIndex(
    $indexName,
    array('url_path'),
    array(
        'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    )
);*/

$installer->getConnection()->createTable($table);

$installer->catalogSetup()->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'youtube_video', array(
    'label'             => 'Youtube Video',
    'note'              => $installer->getHelper()->__('Enter a link to YouTube video'),
    'frontend_class'    => 'validate-url'
));

$installer->endSetup();
