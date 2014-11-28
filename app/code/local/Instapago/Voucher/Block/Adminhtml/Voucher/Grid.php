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
 * Voucher admin grid block
 *
 * @category    Instapago
 * @package     Instapago_Voucher
 * @author      Ultimate Module Creator
 */

 

class Instapago_Voucher_Block_Adminhtml_Voucher_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->setId('voucherGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
		
    }
    /**
     * prepare collection
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('instapago_voucher/voucher')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('instapago_voucher')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('ordernumber', array(
            'header'    => Mage::helper('instapago_voucher')->__('Número de orden'),
            'align'     => 'left',
            'index'     => 'ordernumber',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('instapago_voucher')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('instapago_voucher')->__('Enabled'),
                '0' => Mage::helper('instapago_voucher')->__('Disabled'),
            )
        ));
        $this->addColumn('id_voucher', array(
            'header'=> Mage::helper('instapago_voucher')->__('Id Voucher'),
            'index' => 'id_voucher',
            'type'=> 'text',

        ));
        $this->addColumn('code', array(
            'header'=> Mage::helper('instapago_voucher')->__('Code'),
            'index' => 'code',
            'type'=> 'text',

        ));
        $this->addColumn('success', array(
            'header'=> Mage::helper('instapago_voucher')->__('Success'),
            'index' => 'success',
            'type'=> 'text',

        ));
         
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('instapago_voucher')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('instapago_voucher')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('instapago_voucher')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        
        $this->addExportType('*/*/exportCsv', Mage::helper('instapago_voucher')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('instapago_voucher')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('instapago_voucher')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('voucher');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('instapago_voucher')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('instapago_voucher')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('instapago_voucher')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('instapago_voucher')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('instapago_voucher')->__('Enabled'),
                                '0' => Mage::helper('instapago_voucher')->__('Disabled'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Instapago_Voucher_Model_Voucher
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Instapago_Voucher_Model_Resource_Voucher_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Instapago_Voucher_Block_Adminhtml_Voucher_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
