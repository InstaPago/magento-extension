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
 * Instapago admin controller
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Adminhtml_Instapagomodulo_InstapagoController
    extends Instapago_InstapagoModulo_Controller_Adminhtml_InstapagoModulo {
    /**
     * init the instapago
     * @access protected
     * @return Instapago_InstapagoModulo_Model_Instapago
     */
    protected function _initInstapago(){
        $instapagoId  = (int) $this->getRequest()->getParam('id');
        $instapago    = Mage::getModel('instapago_instapagomodulo/instapago');
        if ($instapagoId) {
            $instapago->load($instapagoId);
        }
        Mage::register('current_instapago', $instapago);
        return $instapago;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('instapago_instapagomodulo')->__('Instapago'))
             ->_title(Mage::helper('instapago_instapagomodulo')->__('Instapago'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit instapago - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction() {
        $instapagoId    = $this->getRequest()->getParam('id');
        $instapago      = $this->_initInstapago();
        if ($instapagoId && !$instapago->getId()) {
            $this->_getSession()->addError(Mage::helper('instapago_instapagomodulo')->__('This instapago no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInstapagoData(true);
        if (!empty($data)) {
            $instapago->setData($data);
        }
        Mage::register('instapago_data', $instapago);
        $this->loadLayout();
        $this->_title(Mage::helper('instapago_instapagomodulo')->__('Instapago'))
             ->_title(Mage::helper('instapago_instapagomodulo')->__('Instapago'));
        if ($instapago->getId()){
            $this->_title($instapago->getTienda());
        }
        else{
            $this->_title(Mage::helper('instapago_instapagomodulo')->__('Añadir credenciales'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new instapago action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save instapago - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('instapago')) {
            try {
                $instapago = $this->_initInstapago();
                $instapago->addData($data);
                $instapago->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_instapagomodulo')->__('Instapago was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $instapago->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInstapagoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('There was a problem saving the instapago.'));
                Mage::getSingleton('adminhtml/session')->setInstapagoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('Unable to find instapago to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete instapago - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $instapago = Mage::getModel('instapago_instapagomodulo/instapago');
                $instapago->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_instapagomodulo')->__('Instapago was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('There was an error deleting instapago.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('Could not find instapago to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete instapago - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction() {
        $instapagoIds = $this->getRequest()->getParam('instapago');
        if(!is_array($instapagoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('Please select instapago to delete.'));
        }
        else {
            try {
                foreach ($instapagoIds as $instapagoId) {
                    $instapago = Mage::getModel('instapago_instapagomodulo/instapago');
                    $instapago->setId($instapagoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_instapagomodulo')->__('Total of %d instapago were successfully deleted.', count($instapagoIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('There was an error deleting instapago.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction(){
        $instapagoIds = $this->getRequest()->getParam('instapago');
        if(!is_array($instapagoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('Please select instapago.'));
        }
        else {
            try {
                foreach ($instapagoIds as $instapagoId) {
                $instapago = Mage::getSingleton('instapago_instapagomodulo/instapago')->load($instapagoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d instapago were successfully updated.', count($instapagoIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('There was an error updating instapago.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass StatusId change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusidAction(){
        $instapagoIds = $this->getRequest()->getParam('instapago');
        if(!is_array($instapagoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('Please select instapago.'));
        }
        else {
            try {
                foreach ($instapagoIds as $instapagoId) {
                $instapago = Mage::getSingleton('instapago_instapagomodulo/instapago')->load($instapagoId)
                            ->setStatusid($this->getRequest()->getParam('flag_statusid'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d instapago were successfully updated.', count($instapagoIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_instapagomodulo')->__('There was an error updating instapago.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction(){
        $fileName   = 'instapago.csv';
        $content    = $this->getLayout()->createBlock('instapago_instapagomodulo/adminhtml_instapago_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction(){
        $fileName   = 'instapago.xls';
        $content    = $this->getLayout()->createBlock('instapago_instapagomodulo/adminhtml_instapago_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction(){
        $fileName   = 'instapago.xml';
        $content    = $this->getLayout()->createBlock('instapago_instapagomodulo/adminhtml_instapago_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/instapago_instapagomodulo/instapago');
    }
}
