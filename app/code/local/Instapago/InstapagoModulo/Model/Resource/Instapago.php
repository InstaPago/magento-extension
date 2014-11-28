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
 * Instapago resource model
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Model_Resource_Instapago
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * constructor
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct(){
        $this->_init('instapago_instapagomodulo/instapago', 'entity_id');
    }
    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $instapagoId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($instapagoId){
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('instapago_instapagomodulo/instapago_store'), 'store_id')
            ->where('instapago_id = ?',(int)$instapagoId);
        return $adapter->fetchCol($select);
    }
    /**
     * Perform operations after object load
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Instapago_InstapagoModulo_Model_Resource_Instapago
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object){
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Instapago_InstapagoModulo_Model_Instapago $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('instapagomodulo_instapago_store' => $this->getTable('instapago_instapagomodulo/instapago_store')),
                $this->getMainTable() . '.entity_id = instapagomodulo_instapago_store.instapago_id',
                array()
            )
            ->where('instapagomodulo_instapago_store.store_id IN (?)', $storeIds)
            ->order('instapagomodulo_instapago_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }
    /**
     * Assign instapago to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Instapago_InstapagoModulo_Model_Resource_Instapago
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object){
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('instapago_instapagomodulo/instapago_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'instapago_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'instapago_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }}
