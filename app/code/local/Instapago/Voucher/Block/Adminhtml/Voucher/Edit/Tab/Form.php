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
 * Voucher edit form tab
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Block_Adminhtml_Voucher_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('voucher_');
        $form->setFieldNameSuffix('voucher');
        $this->setForm($form);
        $fieldset = $form->addFieldset('voucher_form', array('legend'=>Mage::helper('instapago_voucher')->__('Voucher')));

        $fieldset->addField('ordernumber', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Número de orden'),
            'name'  => 'ordernumber',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('message', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Mensaje'),
            'name'  => 'message',

        ));

        $fieldset->addField('id_voucher', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Id Voucher'),
            'name'  => 'id_voucher',

        ));

        $fieldset->addField('code', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Code'),
            'name'  => 'code',

        ));

        $fieldset->addField('reference', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Reference'),
            'name'  => 'reference',

        ));

        $fieldset->addField('voucher', 'textarea', array(
            'label' => Mage::helper('instapago_voucher')->__('Voucher'),
            'name'  => 'voucher',

        ));

        $fieldset->addField('success', 'text', array(
            'label' => Mage::helper('instapago_voucher')->__('Success'),
            'name'  => 'success',

        ));

        $fieldset->addField('fecha_data', 'date', array(
            'label' => Mage::helper('instapago_voucher')->__('Fecha'),
            'name'  => 'fecha_data',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('instapago_voucher')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('instapago_voucher')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('instapago_voucher')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_voucher')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_voucher')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getVoucherData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getVoucherData());
            Mage::getSingleton('adminhtml/session')->setVoucherData(null);
        }
        elseif (Mage::registry('current_voucher')){
            $formValues = array_merge($formValues, Mage::registry('current_voucher')->getData());
        }
 
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
