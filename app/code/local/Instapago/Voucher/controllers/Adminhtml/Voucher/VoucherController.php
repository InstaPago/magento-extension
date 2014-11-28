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
 * Voucher admin controller
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Adminhtml_Voucher_VoucherController
    extends Instapago_Voucher_Controller_Adminhtml_Voucher {
    /**
     * init the voucher
     * @access protected
     * @return Instapago_Voucher_Model_Voucher
     */
    protected function _initVoucher(){
        $voucherId  = (int) $this->getRequest()->getParam('id');
        $voucher    = Mage::getModel('instapago_voucher/voucher');
        if ($voucherId) {
            $voucher->load($voucherId);
        }
        Mage::register('current_voucher', $voucher);
        return $voucher;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('instapago_voucher')->__('Voucher'))
             ->_title(Mage::helper('instapago_voucher')->__('Voucher'));
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
     * edit voucher - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction() {
        $voucherId    = $this->getRequest()->getParam('id');
        $voucher      = $this->_initVoucher();
        if ($voucherId && !$voucher->getId()) {
            $this->_getSession()->addError(Mage::helper('instapago_voucher')->__('This voucher no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getVoucherData(true);
        if (!empty($data)) {
            $voucher->setData($data);
        }
        Mage::register('voucher_data', $voucher);
        $this->loadLayout();
        $this->_title(Mage::helper('instapago_voucher')->__('Voucher'))
             ->_title(Mage::helper('instapago_voucher')->__('Voucher'));
        if ($voucher->getId()){
            $this->_title($voucher->getOrdernumber());
        }
        else{
            $this->_title(Mage::helper('instapago_voucher')->__('Add voucher'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new voucher action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save voucher - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
		public function saveActionVou($dataq) { 
		$voucher = $this->_initVoucher();		
		$voucher->addData($dataq);
		$voucher->save();		
		return true;			
		}  
		
		
		
		public function saveAction() {
        if ($data = $this->getRequest()->getPost('voucher')) {
            try {
                $data = $this->_filterDates($data, array('fecha_data'));
                $voucher = $this->_initVoucher();
                $voucher->addData($data);
                $voucher->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_voucher')->__('Voucher was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $voucher->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setVoucherData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('There was a problem saving the voucher.'));
                Mage::getSingleton('adminhtml/session')->setVoucherData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('Unable to find voucher to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete voucher - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $voucher = Mage::getModel('instapago_voucher/voucher');
                $voucher->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_voucher')->__('Voucher was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('There was an error deleting voucher.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('Could not find voucher to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete voucher - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction() {
        $voucherIds = $this->getRequest()->getParam('voucher');
        if(!is_array($voucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('Please select voucher to delete.'));
        }
        else {
            try {
                foreach ($voucherIds as $voucherId) {
                    $voucher = Mage::getModel('instapago_voucher/voucher');
                    $voucher->setId($voucherId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('instapago_voucher')->__('Total of %d voucher were successfully deleted.', count($voucherIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('There was an error deleting voucher.'));
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
        $voucherIds = $this->getRequest()->getParam('voucher');
        if(!is_array($voucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('Please select voucher.'));
        }
        else {
            try {
                foreach ($voucherIds as $voucherId) {
                $voucher = Mage::getSingleton('instapago_voucher/voucher')->load($voucherId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d voucher were successfully updated.', count($voucherIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('instapago_voucher')->__('There was an error updating voucher.'));
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
        $fileName   = 'voucher.csv';
        $content    = $this->getLayout()->createBlock('instapago_voucher/adminhtml_voucher_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction(){
        $fileName   = 'voucher.xls';
        $content    = $this->getLayout()->createBlock('instapago_voucher/adminhtml_voucher_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction(){
        $fileName   = 'voucher.xml';
        $content    = $this->getLayout()->createBlock('instapago_voucher/adminhtml_voucher_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/instapago_voucher/voucher');
    }
}
