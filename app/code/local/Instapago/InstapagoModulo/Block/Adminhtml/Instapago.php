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
 * Instapago admin block
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Block_Adminhtml_Instapago
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_instapago';
        $this->_blockGroup         = 'instapago_instapagomodulo';
        parent::__construct();
        $this->_headerText         = Mage::helper('instapago_instapagomodulo')->__('Credenciales Instapago');
        $this->_updateButton('add', 'label', Mage::helper('instapago_instapagomodulo')->__('Añadir credenciales'));

    }
}
