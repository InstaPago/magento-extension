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
 * Instapago edit form tab
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('instapago_');
        $form->setFieldNameSuffix('instapago');
        $this->setForm($form);
        $fieldset = $form->addFieldset('instapago_form', array('legend'=>Mage::helper('instapago_instapagomodulo')->__('Instapago')));

        $fieldset->addField('tienda', 'text', array(
            'label' => Mage::helper('instapago_instapagomodulo')->__('Tienda'),
            'name'  => 'tienda',
            'note'	=> $this->__('Colocar el identificador de la tienda en caso de poseer múltiples tiendas. Si no es así, valor por defecto debe ser 1'),
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('keyid', 'text', array(
            'label' => Mage::helper('instapago_instapagomodulo')->__('Keyid'),
            'name'  => 'keyid',
            'note'	=> $this->__('Llave generada desde Instapago'),
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('publickeyid', 'text', array(
            'label' => Mage::helper('instapago_instapagomodulo')->__('PublickeyId'),
            'name'  => 'publickeyid',
            'note'	=> $this->__('Llave compartida Enviada por correo al crear una cuenta  en instapago'),
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('statusid', 'select', array(
            'label' => Mage::helper('instapago_instapagomodulo')->__('StatusId'),
            'name'  => 'statusid',
            'note'	=> $this->__('Estatus en el que se creará la transacción.'),
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('instapago_instapagomodulo/instapago_attribute_source_statusid')->getAllOptions(true),
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('instapago_instapagomodulo')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('instapago_instapagomodulo')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('instapago_instapagomodulo')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_instapago')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_instapago')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getInstapagoData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getInstapagoData());
            Mage::getSingleton('adminhtml/session')->setInstapagoData(null);
        }
        elseif (Mage::registry('current_instapago')){
            $formValues = array_merge($formValues, Mage::registry('current_instapago')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
