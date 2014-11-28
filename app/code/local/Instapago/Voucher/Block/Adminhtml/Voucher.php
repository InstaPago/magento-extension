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
 * Voucher admin block
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Block_Adminhtml_Voucher
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_voucher';
        $this->_blockGroup         = 'instapago_voucher';
        parent::__construct();
        $this->_headerText         = Mage::helper('instapago_voucher')->__('Instapago Voucher');
        $this->_updateButton('add', 'label', Mage::helper('instapago_voucher')->__('Add Voucher'));
		$this->_removeButton('add');

    }
}
