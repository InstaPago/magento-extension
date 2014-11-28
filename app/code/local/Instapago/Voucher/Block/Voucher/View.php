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
 * Voucher view block
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Block_Voucher_View
    extends Mage_Core_Block_Template {
    /**
     * get the current voucher
     * @access public
     * @return mixed (Instapago_Voucher_Model_Voucher|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentVoucher(){
        return Mage::registry('current_voucher');
    }
}
