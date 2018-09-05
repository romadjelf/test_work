<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 27.08.18
 * Time: 16:24
 */

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('zhupanyn_imgloader/list');

/*$indexName = $installer->getConnection()->getIndexName(
    $tableName,
    array('url_path'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);*/
$installer->getConnection()->dropTable($tableName);

// addColumn функция Varien_Db_Ddl_Table
//$a = new Varien_Db_Ddl_Table(); $a->addColumn();

$table = $installer->getConnection()->newTable($tableName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true
        ),
        'ID of row'
    )
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64,
        array(
            'nullable' => false,
        ),
        'SKU of product'
    )
    ->addColumn('create_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
        array(
            'nullable' => false
        ),
        'Create Datetime'
    )
    ->addColumn('update_datetime', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
        array(),
        'Update Datetime'
    )
    ->addColumn('img_url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => false
        ),
        'Image Url'
    )
    ->addColumn('img_size', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true
        ),
        'Image Size'
    )
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null,
        array(
            'nullable' => false,
            'default' => 1,
            'unsigned' => true
        ),
        'Status: 1 - в очереди, 2 - повторная попытка, 3 - загружено, 4 - ошибка'
    )
    ->addColumn('error_text', Varien_Db_Ddl_Table::TYPE_TEXT, null,
        array(),
        'Error Text'
    );

/*$table->addIndex(
    $indexName,
    array('url_path'),
    array(
        'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    )
);*/

$installer->getConnection()->createTable($table);

//$installer->run("");

$installer->endSetup();
