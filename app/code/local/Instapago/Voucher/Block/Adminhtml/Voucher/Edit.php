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
 * Voucher admin edit form
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Block_Adminhtml_Voucher_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'instapago_voucher';
        $this->_controller = 'adminhtml_voucher';
        $this->_updateButton('save', 'label', Mage::helper('instapago_voucher')->__('Save Voucher'));
        $this->_updateButton('delete', 'label', Mage::helper('instapago_voucher')->__('Delete Voucher'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('instapago_voucher')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText(){
        if( Mage::registry('current_voucher') && Mage::registry('current_voucher')->getId() ) {
            return Mage::helper('instapago_voucher')->__("Edit Voucher '%s'", $this->escapeHtml(Mage::registry('current_voucher')->getOrdernumber()));
        }
        else {
            return Mage::helper('instapago_voucher')->__('Add Voucher');
        }
    }
}
