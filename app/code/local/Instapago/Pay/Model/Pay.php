<?php
class Instapago_Pay_Model_Pay extends Mage_Payment_Model_Method_Cc
{
	protected $_code = 'pay';
	protected $_formBlockType = 'pay/form_pay';
	protected $_infoBlockType = 'pay/info_pay';

	protected $_isGateway               = true;
	protected $_canAuthorize            = true;
	protected $_canCapture              = true;
	//protected $_canCapturePartial       = true;
	protected $_canRefund               = false;


	protected $_canSaveCc = false; //if made try, the actual credit card number and cvv code are stored in database.

	//protected $_canRefundInvoicePartial = true;
	//protected $_canVoid                 = true;
	//protected $_canUseInternal          = true;
	//protected $_canUseCheckout          = true;
	//protected $_canUseForMultishipping  = true;
	//protected $_canFetchTransactionInfo = true;
	//protected $_canReviewPayment        = true;


	public function validate()
	{
		parent::validate();
	 	
		$productId00 = Mage::app()->getRequest()->getPost();
		$_SESSION['ci']= $productId00['payment']['cc_ci'];
		if(empty($productId00['payment']['cc_ci'])){
				
		
		
			$errorCode = 'invalid_data';
			$errorMsg = $this->_getHelper()->__('Por favor llene todo los campos.');
	
	}
		
		if($errorMsg){
			Mage::throwException($errorMsg);
		}
	
		return $this;
	}
	
	
	public function process($data){

		if($data['cancel'] == 1){
		 $order->getPayment()
		 ->setTransactionId(null)
		 ->setParentTransactionId(time())
		 ->void();
		 $message = 'Unable to process Payment';
		 $order->registerCancellation($message)->save();
		}
	 
	}

	/** For capture **/
	public function capture(Varien_Object $payment, $amount)
	{
		$order = $payment->getOrder();
		$result = $this->callApi($payment,$amount,'authorize');
		if($result === false) {
			$errorCode = 'Invalid Data';
			$errorMsg = $this->_getHelper()->__('Error Processing the request');
		} else {
			Mage::log($result, null, $this->getCode().'.log');
			//process result here to check status etc as per payment gateway.
			// if invalid status throw exception

			if($result['status'] == 1){
				$payment->setTransactionId($result['transaction_id']);
				$payment->setIsTransactionClosed(1);
				$payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('key1'=>'value1','key2'=>'value2'));
			}else{
				Mage::throwException($errorMsg);
			}

			// Add the comment and save the order
		}
		if($errorMsg){
			Mage::throwException($errorMsg);
		}
	 
		return $this;
	}


	/** For authorization **/
	public function authorize(Varien_Object $payment, $amount)
	{
		$order = $payment->getOrder();
		
		

		$result = $this->callApi($payment,$amount,'authorize');
	//	var_dump ($result['code0']);
		//if($result === false) {
		if($result['status'] == 0) {
			
			if($result['code0'] ==400) {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Su tarjeta no pudo ser procesada, verifique los datos suministrados e intenté nuevamente.');
			}
			
			if($result['code0'] == '401') {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Error de autenticación. Error '.$result['code0'].'Contacte con el administrador');
			}
			
			if($result['code0'] == '403') {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Pago Rechazado por el banco.');
			}
			
			if($result['code0'] == '500') {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Ha Ocurrido un error interno dentro del servidor.');
			}
			if($result['code0'] == '503') {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Ha Ocurrido un error al procesar los parámetros de entrada. Revise los datos
				enviados y vuelva a intentarlo.');
			}
			if($result['code0'] == '' or $result['code0'] == ' ' or $result['code0'] == null ) {
				$errorCode = 'Invalid Data';
				$errorMsg = $this->_getHelper()->__('Error al procesar la petición.');
			}
 

		
			
			
		} else {
			
			//var_dump (Mage::log($result, null, $this->getCode().'.log'));
			Mage::log($result, null, $this->getCode().'.log');
			//process result here to check status etc as per payment gateway.
			// if invalid status throw exception

			if($result['status'] == 1){
				$payment->setTransactionId($result['transaction_id']);
				/*
				 * This marks transactions as closed or open
				*/
				$payment->setIsTransactionClosed(1);
				/*
				 * This basically makes order status to be payment review and no invoice is created.
				* and adds a default comment like
				* Authorizing amount of $17.00 is pending approval on gateway. Transaction ID: "1335419269".
				*
				*/
				//$payment->setIsTransactionPending(true);
				/*
				 * This basically makes order status to be processing and no invoice is created.
				* add a default comment to order like
				* Authorized amount of $17.00. Transaction ID: "1335419459".
				*/
				//$payment->setIsTransactionApproved(true);

				/*
				 * This method is used to display extra informatoin on transaction page
				*/
				$payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,array('Codigo de Respuesta'=>$result['code0'],'Id'=>$result['id'],'result_json'=>$result['result_json'],'Respuesta'=>$result['message']));


				$order->addStatusToHistory($order->getStatus(), 'Payment Sucessfully Placed with Transaction ID'.$result['transaction_id'], false);
			
				$order->save();
			}else{
				Mage::throwException($errorMsg);
			}

			// Add the comment and save the order
		}
		if($errorMsg){
			Mage::throwException($errorMsg);
		} 
	/*
		var_dump ($this->getOrderId());
		var_dump ($_SESSION['result_json']);
		var_dump ($_SESSION['checkout']['last_real_order_id']);
		/*/
		//$sql_data ='INSERT INTO '".Mage::getSingleton('core/resource')->getDatabaseName()."' ."instapago_voucher_voucher" ("entity_id", "ordernumber", "message", "id_voucher", "code", "reference", "voucher", "success", "fecha_data", "url_key", "updated_at", "status", "created_at") VALUES (NULL, "'.$_SESSION["checkout"]["last_real_order_id"].'", "'.$_SESSION["result_json"]["message"].'"," '.$_SESSION["result_json"]["id"].'"," '.$_SESSION["result_json"]["code"].'"," '. $_SESSION["result_json"]["reference"].'", "'.$_SESSION["result_json"]["voucher"].'", "'.$_SESSION["result_json"]["success"].'", "2014-11-05 00:00:00", NULL, NOW(), "1", NOW())'; 
		
		
		
		/*
			$cadena =$_SESSION["result_json"]["voucher"];
		 
			$cadena =	 htmlentities($cadena);
			$cadena2 = html_entity_decode($cadena);
			
			echo $cadena;
			echo $cadena2;
			*/
			//echo($sql_data);
			
			/*
			
			//echo $cadena;
			
			echo "<br />";
			echo "<br />";
			
			$resultado = str_replace("'0'", "''0''", $cadena);
			
			
			//$resultado = str_replace('"', "'", $resultado);
			
			echo "<br />";
			echo "<br />";
			echo "La cadena resultante es: " . $resultado;
			
			*/
			
			
			
			
		
		
		
		//echo($sql_data);
		//var_dump ($_SESSION);
	//exit;
		 
		//	var_dump($this);
		return $this;
	}

	public function processBeforeRefund($invoice, $payment){
		return parent::processBeforeRefund($invoice, $payment);
	}
	public function refund(Varien_Object $payment, $amount){
		$order = $payment->getOrder();
		$result = $this->callApi($payment,$amount,'refund');
		if($result === false) {
			$errorCode = 'Invalid Data';
			$errorMsg = $this->_getHelper()->__('Error Processing the request');
			Mage::throwException($errorMsg);
		}
		return $this;

	}
	public function processCreditmemo($creditmemo, $payment){
		return parent::processCreditmemo($creditmemo, $payment);
	}

	private function callApi(Varien_Object $payment, $amount,$type){
		
 
 

		//call your authorize api here, incase of error throw exception.
		//only example code written below to show flow of code

 
		 $order = $payment->getOrder();
		 
		$types = Mage::getSingleton('payment/config')->getCcTypes();
		if (isset($types[$payment->getCcType()])) {
		$type = $types[$payment->getCcType()];
		}
	
		$billingaddress = $order->getBillingAddress();
		$totals = number_format($amount, 2, '.', '');
		$orderId = $order->getIncrementId();
		
 
	 
		 
		/* wwwwwwwwwwwwwwwwwwww */
		
		$read= Mage::getSingleton('core/resource')->getConnection('core_read'); 
		$instapago_inf = $read->fetchAll("select * from instapago_instapagomodulo_instapago"); 
		
		//var_dump ($instapago_inf);
		$storeId = Mage::app()->getStore()->getStoreId();
		foreach ($instapago_inf as &$valor) {
			
			if ($valor['status'] == "1") {	
				if ($valor['tienda'] == $storeId) {
					$url = 'https://api.instapago.com/payment';
 
					if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip=$_SERVER['REMOTE_ADDR'];
					}
					
					$fields = array(
					'KeyID'=> $valor['keyid'],
					'PublicKeyId'=> $valor['publickeyid'],
					'amount'=>$totals,
					'Description'=> 'Tienda: '.$storeId.' numero de orden:'.$order->getIncrementId(),
					//'Description'=> 'instapago_magento',
					'CardHolder'=> $payment->getCcOwner(),
					'CardHolderId'=> $_SESSION['ci'],
					'CardNumber'=>  $payment->getCcNumber(),
					'CVC'=>  $payment->getCcCid(),
					'ExpirationDate'=>  $payment->getCcExpMonth()."/".$payment->getCcExpYear(),
					'StatusId'=>  $valor['statusid'],
					'Address'=>  '',
					'City'=>  '',
					'ZipCode'=>  '',
					'State'=>'',
					'customer_ipaddress'=> $ip
			 );
			 
				
		
					$fields_string="";
					foreach($fields as $key=>$value) {
						$fields_string .= $key.'='.$value.'&';
					}
					$fields_string = substr($fields_string,0,-1);
	 
		
					//var_dump ($fields_string);
					//open connection
					$ch = curl_init($url);
					//set the url, number of POST vars, POST data
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
					curl_setopt($ch, CURLOPT_HEADER ,0); // DO NOT RETURN HTTP HEADERS
					curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); // RETURN THE CONTENTS OF THE CALL
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)
					//execute post
					$result = curl_exec($ch);
					//var_dump ($result);	
					//var_dump (json_decode($result));
					
					$result_json = json_decode($result, true);
			//var_dump($result_json);
		 	$_SESSION['result_json']= $result_json;
	if ( $result_json['success'] == false) {$status00 =0;	}
	else if ( $result_json['success'] == true) {$status00 =1;	}
			
				
			 
					curl_close($ch);
					 
					//return array('status'=>rand(0, 1),'transaction_id' => time() , 'fraud' => rand(0,1));
					return array('status'=>$status00,'transaction_id' => time() , 'fraud' => rand(0,1),'code0' =>$result_json['code'],'result_json' =>$result_json,$result_json['message'],'id' =>$result_json['id']);
					
					
				}
			}		
			
		}
		
		
		
		/* wwwwwwwwwwwwwwwwwwww */
		
		
		
		
		
		
		
		
		
		
		
 

		
		
	//return array('status'=>rand(0, 1),'transaction_id' => time() , 'fraud' => rand(0,1));
	}

	/*
	public function getOrderPlaceRedirectUrl()
	{
		if((int)$this->_getOrderAmount() > 0){
			return Mage::getUrl('pay/index/index', array('_secure' => true));
		}else{
			return false;
		}
	}
	private function _getOrderAmount()
	{
		$info = $this->getInfoInstance();
		if ($this->_isPlacedOrder()) {
			return (double)$info->getOrder()->getQuoteBaseGrandTotal();
		} else {
			return (double)$info->getQuote()->getBaseGrandTotal();
		}
	}
	private function _isPlacedOrder()
	{
		$info = $this->getInfoInstance();
		if ($info instanceof Mage_Sales_Model_Quote_Payment) {
			return false;
		} elseif ($info instanceof Mage_Sales_Model_Order_Payment) {
			return true;
		}
	}
	*/
}
?>
