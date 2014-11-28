<?php
/**
 * Instapago_InstapagoModulo extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Instapago
 * @package        Instapago_InstapagoModulo
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * InstapagoModulo module install script
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('instapago_instapagomodulo/instapago'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Instapago ID')
    ->addColumn('tienda', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Tienda')

    ->addColumn('keyid', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Keyid')

    ->addColumn('publickeyid', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'PublickeyId')

    ->addColumn('statusid', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        ), 'StatusId')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        ), 'Enabled')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Instapago Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Instapago Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Instapago Creation Time') 
    ->setComment('Instapago Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('instapago_instapagomodulo/instapago_store'))
    ->addColumn('instapago_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Instapago ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('instapago_instapagomodulo/instapago_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('instapago_instapagomodulo/instapago_store', 'instapago_id', 'instapago_instapagomodulo/instapago', 'entity_id'), 'instapago_id', $this->getTable('instapago_instapagomodulo/instapago'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('instapago_instapagomodulo/instapago_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Instapago To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
