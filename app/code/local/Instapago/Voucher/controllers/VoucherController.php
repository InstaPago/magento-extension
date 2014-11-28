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
 * Voucher front contrller
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_VoucherController
    extends Mage_Core_Controller_Front_Action {
    /**
      * default action
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction(){
         $this->loadLayout();
         $this->_initLayoutMessages('catalog/session');
         $this->_initLayoutMessages('customer/session');
         $this->_initLayoutMessages('checkout/session');
         if (Mage::helper('instapago_voucher/voucher')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('instapago_voucher')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('vouchers', array(
                            'label'    => Mage::helper('instapago_voucher')->__('Voucher'),
                            'link'    => '',
                    )
                 );
             }
         }
        $this->renderLayout();
    }
    /**
     * init Voucher
     * @access protected
     * @return Instapago_Voucher_Model_Entity
     * @author Ultimate Module Creator
     */
    protected function _initVoucher(){
        $voucherId   = $this->getRequest()->getParam('id', 0);
        $voucher     = Mage::getModel('instapago_voucher/voucher')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($voucherId);
        if (!$voucher->getId()){
            return false;
        }
        elseif (!$voucher->getStatus()){
            return false;
        }
        return $voucher;
    }
    /**
      * view voucher action
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function viewAction(){
        $voucher = $this->_initVoucher();
        if (!$voucher) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_voucher', $voucher);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('voucher-voucher voucher-voucher' . $voucher->getId());
        }
        if (Mage::helper('instapago_voucher/voucher')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('instapago_voucher')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('vouchers', array(
                            'label'    => Mage::helper('instapago_voucher')->__('Voucher'),
                            'link'    => Mage::helper('instapago_voucher/voucher')->getVouchersUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('voucher', array(
                            'label'    => $voucher->getOrdernumber(),
                            'link'    => '',
                    )
                );
            }
        }
        $this->renderLayout();
    }
}
