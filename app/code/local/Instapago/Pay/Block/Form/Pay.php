<?php
class Instapago_Pay_Block_Form_Pay extends Mage_Payment_Block_Form_Ccsave
{
	protected function _construct()
	{
		parent::_construct();
		//$this->setTemplate('pay/form/pay.phtml');
		//$this->setTemplate('payment/form/pay.phtml');
		$this->setTemplate('payment/form/cc2.phtml');
		//$this->setTemplate('payment/form/ccsave2.phtml'); 
	}
	
}