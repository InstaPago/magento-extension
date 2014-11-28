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
 * Voucher helper
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Helper_Voucher
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the voucher list page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getVouchersUrl(){
        if ($listKey = Mage::getStoreConfig('instapago_voucher/voucher/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('instapago_voucher/voucher/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('instapago_voucher/voucher/breadcrumbs');
    }
}
