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
 * Instapago admin edit tabs
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        parent::__construct();
        $this->setId('instapago_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('instapago_instapagomodulo')->__('Instapago'));
    }
    /**
     * before render html
     * @access protected
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml(){
        $this->addTab('form_instapago', array(
            'label'        => Mage::helper('instapago_instapagomodulo')->__('Instapago'),
            'title'        => Mage::helper('instapago_instapagomodulo')->__('Instapago'),
            'content'     => $this->getLayout()->createBlock('instapago_instapagomodulo/adminhtml_instapago_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_instapago', array(
                'label'        => Mage::helper('instapago_instapagomodulo')->__('Store views'),
                'title'        => Mage::helper('instapago_instapagomodulo')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('instapago_instapagomodulo/adminhtml_instapago_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve instapago entity
     * @access public
     * @return Instapago_InstapagoModulo_Model_Instapago
     * @author Ultimate Module Creator
     */
    public function getInstapago(){
        return Mage::registry('current_instapago');
    }
}
