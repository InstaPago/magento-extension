<?php
/**
 * Instapago_Voucher extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Instapago
 * @package        Instapago_Voucher
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Voucher module install script
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('instapago_voucher/voucher'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Voucher ID')
    ->addColumn('ordernumber', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Número de orden')

    ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Mensaje')

    ->addColumn('id_voucher', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Id Voucher')

    ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Code')

    ->addColumn('reference', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Reference')

    ->addColumn('voucher', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Voucher')

    ->addColumn('success', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Success')

    ->addColumn('fecha_data', Varien_Db_Ddl_Table::TYPE_DATETIME, 255, array(
        ), 'Fecha')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        ), 'Enabled')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Voucher Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Voucher Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Voucher Creation Time') 
    ->setComment('Voucher Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('instapago_voucher/voucher_store'))
    ->addColumn('voucher_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Voucher ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('instapago_voucher/voucher_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('instapago_voucher/voucher_store', 'voucher_id', 'instapago_voucher/voucher', 'entity_id'), 'voucher_id', $this->getTable('instapago_voucher/voucher'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('instapago_voucher/voucher_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Voucher To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
