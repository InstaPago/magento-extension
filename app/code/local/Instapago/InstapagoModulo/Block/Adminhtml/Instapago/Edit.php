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
 * Instapago admin edit form
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'instapago_instapagomodulo';
        $this->_controller = 'adminhtml_instapago';
        $this->_updateButton('save', 'label', Mage::helper('instapago_instapagomodulo')->__('Guardar credenciales'));
        $this->_updateButton('delete', 'label', Mage::helper('instapago_instapagomodulo')->__('Borrar credenciales'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('instapago_instapagomodulo')->__('Guardar y continuar editando'),
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
        if( Mage::registry('current_instapago') && Mage::registry('current_instapago')->getId() ) {
            return Mage::helper('instapago_instapagomodulo')->__("Editar credenciales en la tienda '%s'", $this->escapeHtml(Mage::registry('current_instapago')->getTienda()));
        }
        else {
            return Mage::helper('instapago_instapagomodulo')->__('Añadir credenciales');
        }
    }
}
