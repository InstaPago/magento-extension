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
 * Voucher admin edit tabs
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Block_Adminhtml_Voucher_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        parent::__construct();
        $this->setId('voucher_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('instapago_voucher')->__('Voucher'));
    }
    /**
     * before render html
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml(){
        $this->addTab('form_voucher', array(
            'label'        => Mage::helper('instapago_voucher')->__('Voucher'),
            'title'        => Mage::helper('instapago_voucher')->__('Voucher'),
            'content'     => $this->getLayout()->createBlock('instapago_voucher/adminhtml_voucher_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_voucher', array(
                'label'        => Mage::helper('instapago_voucher')->__('Store views'),
                'title'        => Mage::helper('instapago_voucher')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('instapago_voucher/adminhtml_voucher_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve voucher entity
     * @access public
     * @return Instapago_Voucher_Model_Voucher
     * @author Ultimate Module Creator
     */
    public function getVoucher(){
        return Mage::registry('current_voucher');
    }
}
