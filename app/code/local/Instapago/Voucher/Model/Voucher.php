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
 * Voucher model
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */
class Instapago_Voucher_Model_Voucher
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'instapago_voucher_voucher';
    const CACHE_TAG = 'instapago_voucher_voucher';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'instapago_voucher_voucher';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'voucher';
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct(){
        parent::_construct();
        $this->_init('instapago_voucher/voucher');
    }
    /**
     * before save voucher
     * @access protected
     * @return Instapago_Voucher_Model_Voucher
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * get the url to the voucher details page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getVoucherUrl(){
        return Mage::getUrl('instapago_voucher/voucher/view', array('id'=>$this->getId()));
    }
    /**
     * save voucher relation
     * @access public
     * @return Instapago_Voucher_Model_Voucher
     * @author Ultimate Module Creator
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
}
