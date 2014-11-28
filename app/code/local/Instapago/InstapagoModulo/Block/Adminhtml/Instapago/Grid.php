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
 * Instapago admin grid block
 *
 * @category    Instapago
 * @package     Instapago_InstapagoModulo
 * @author      Ultimate Module Creator
 */
class Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->setId('instapagoGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('instapago_instapagomodulo/instapago')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('instapago_instapagomodulo')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('tienda', array(
            'header'    => Mage::helper('instapago_instapagomodulo')->__('Tienda'),
            'align'     => 'left',
            'index'     => 'tienda',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('instapago_instapagomodulo')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('instapago_instapagomodulo')->__('Enabled'),
                '0' => Mage::helper('instapago_instapagomodulo')->__('Disabled'),
            )
        ));
        $this->addColumn('keyid', array(
            'header'=> Mage::helper('instapago_instapagomodulo')->__('Keyid'),
            'index' => 'keyid',
            'type'=> 'text',

        ));
        $this->addColumn('publickeyid', array(
            'header'=> Mage::helper('instapago_instapagomodulo')->__('PublickeyId'),
            'index' => 'publickeyid',
            'type'=> 'text',

        ));
        $this->addColumn('statusid', array(
            'header'=> Mage::helper('instapago_instapagomodulo')->__('StatusId'),
            'index' => 'statusid',
            'type'  => 'options',
            'options' => Mage::helper('instapago_instapagomodulo')->convertOptions(Mage::getModel('instapago_instapagomodulo/instapago_attribute_source_statusid')->getAllOptions(false))

        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('instapago_instapagomodulo')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('instapago_instapagomodulo')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('instapago_instapagomodulo')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('instapago_instapagomodulo')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('instapago_instapagomodulo')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('instapago_instapagomodulo')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('instapago_instapagomodulo')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('instapago_instapagomodulo')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('instapago');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('instapago_instapagomodulo')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('instapago_instapagomodulo')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('instapago_instapagomodulo')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('instapago_instapagomodulo')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('instapago_instapagomodulo')->__('Enabled'),
                                '0' => Mage::helper('instapago_instapagomodulo')->__('Disabled'),
                        )
                )
            )
        ));
        $this->getMassactionBlock()->addItem('statusid', array(
            'label'=> Mage::helper('instapago_instapagomodulo')->__('Change StatusId'),
            'url'  => $this->getUrl('*/*/massStatusid', array('_current'=>true)),
            'additional' => array(
                'flag_statusid' => array(
                        'name' => 'flag_statusid',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('instapago_instapagomodulo')->__('StatusId'),
                        'values' => Mage::getModel('instapago_instapagomodulo/instapago_attribute_source_statusid')->getAllOptions(true),

                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Instapago_InstapagoModulo_Model_Instapago
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
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Instapago_InstapagoModulo_Model_Resource_Instapago_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Instapago_InstapagoModulo_Block_Adminhtml_Instapago_Grid
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
