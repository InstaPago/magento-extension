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
 * Voucher list block
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author Ultimate Module Creator
 */
class Instapago_Voucher_Block_Voucher_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Ultimate Module Creator
     */
     public function __construct(){
        parent::__construct();
         $vouchers = Mage::getResourceModel('instapago_voucher/voucher_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $vouchers->setOrder('ordernumber', 'asc');
        $this->setVouchers($vouchers);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Instapago_Voucher_Block_Voucher_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'instapago_voucher.voucher.html.pager')
            ->setCollection($this->getVouchers());
        $this->setChild('pager', $pager);
        $this->getVouchers()->load();
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
}
