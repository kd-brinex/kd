<?php
namespace app\modules\user\models;

	Class GetPayment {
		var $MerchantId;
		var $OrderId;
		var $OrderDescription;
		var $ValidUntil;
		var $Amount;
		var $Currency;
		var $PrivateSecurityKey;
		var $ReturnUrl;
		var $FailUrl;
		var $IData;
		var $PNR;
		var $Commission;
		var $RebillAnchor;
		var $TransactionId;
		var $Ip;
		var $Email ;
		var $CardHolderName ;
		var $CardNumber ;
		var $CardExpDate ;
		var $CardCvv ;
		var $Country ;
		var $City ;
		var $Address ;
		var $Zip ;
		var $State ;
		var $Phone ;
		var $Issuer ;
		var $ContentType;
		var $PD;
		var $PARes;
		var $TermUrl;
		var $Language;
		
//Инициализируем класс	
	function  GetPayment()
	{
	$this->Language=$Language;
	$this->MerchantId=$MerchantId;
	$this->PrivateSecurityKey=$PrivateSecurityKey;
	$this->OrderId=$OrderId;
	$this->Amount=$Amount;
	$this->Currency=$Currency;
	$this->OrderDescription=$OrderDescription;
	$this->ValidUntil=$ValidUntil;
	$this->ReturnUrl=$ReturnUrl;
	$this->FailUrl=$FailUrl;
	$this->IData=$IData;
	$this->PNR=$PNR;
	$this->Commission=$Commission;
	$this->RebillAnchor=$RebillAnchor;
	$this->TransactionId=$TransactionId;
	$this->Ip=$Ip;
	$this->Email=$Email;
	$this->CardHolderName=$CardHolderName;
	$this->CardNumber=$CardNumber;
	$this->CardExpDate=$CardExpDate;
	$this->CardCvv=$CardCvv;
	$this->Country=$Country;
	$this->City=$City;
	$this->Address=$Address;
	$this->Zip=$Zip;
	$this->State=$State;
	$this->Phone=$Phone;
	$this->Issuer=$Issuer;
	$this->ContentType=$ContentType;
	$this->PD=$pd;
	$this->PARes=$pares;
	$this->TermUrl=$TermUrl;
	}
	// генерируем ссылку на оплату по схеме Standart
	function GetPaymentURL()
	{
	$params	 = 'MerchantId='. $this->MerchantId;
	$params .= '&OrderId='.$this->OrderId;
	$params .= '&Amount='. $this->Amount;
	$params .= '&Currency='. $this->Currency;
	if ($this->ValidUntil)
		{
		$params .= '&ValidUntil=' . $this->ValidUntil;
		}
//Авиа потом добавлю		
	if (strlen($this->OrderDescription)<101 AND strlen($this->OrderDescription)>1)
		{
		$params .= '&OrderDescription=' . $this->OrderDescription;
		}	
	$params .= '&PrivateSecurityKey=' . $this->PrivateSecurityKey;
	//echo $params;
	$SecurityKey=md5($params);
	$Paymenturl="https://secure.payonlinesystem.com/".$this->Language."/payment/";
	$url_query= "?MerchantId=".$this->MerchantId."&OrderId=".urlencode($this->OrderId)."&Amount=".$this->Amount."&Currency=".$this->Currency; 
	if ($this->ValidUntil) {$url_query.= "&ValidUntil=".urlencode($this->ValidUntil);} 
	if ($this->OrderDescription) {$url_query.= "&OrderDescription=".urlencode($this->OrderDescription);} 
	if ($this->ReturnUrl) {$url_query.= "&ReturnUrl=".urlencode($this->ReturnUrl);} 
	if ($this->FailUrl) {$url_query.= "&FailUrl=".urlencode($this->FailUrl);} 
	$url_query.="&SecurityKey=".$SecurityKey;
	$url_full=$Paymenturl.$url_query;

	
	return $url_full;
	}
	
	//Метод Complete Real with curl
	function PaymentComplete()
	{
	if ($this->TransactionId AND $this->Amount)
		{
			if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
		global $Result;
		$url="https://secure.payonlinesystem.com/payment/transaction/complete/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="Complete: Неуказан один из параметров TransactionId или Amount";
		}
	//CURL
	$url = $Result;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);   
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	
		return $Result;
	}
	
//Метод Complete получения ссылки
	function GetPaymentCompleteURL()
	{
	if ($this->TransactionId AND $this->Amount)
		{
		$url="https://secure.payonlinesystem.com/payment/transaction/complete/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
   echo $Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="COMPLETE: Неуказан один из параметров TransactionId или Amount";
		}
	
		return $Result;
	}
	
// Метод ребил получение ссылки
	function GetPaymentRebillURL()
	{
	if ($this->Amount AND $this->RebillAnchor AND $this->Currency)
		{
		$url="https://secure.payonlinesystem.com/payment/transaction/rebill/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&RebillAnchor=".$this->RebillAnchor."&OrderId=".$this->OrderId."&Amount=".$this->Amount."&Currency=".$this->Currency."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&RebillAnchor=".urlencode($this->RebillAnchor)."&OrderId=".urlencode($this->OrderId)."&Amount=".$this->Amount."&Currency=".$this->Currency."&SecurityKey=".$SecurityKey;
		}
		else 
		{
		$Result="REBILL: Неуказан один из параметров Amount, RebillAnchor или Currency";
		}
	
		return $Result;
	}
//метод ребил с получением результатов	
	function PaymentRebill()
	{
	if ($this->Amount AND $this->RebillAnchor AND $this->Currency)
		{
		if (strtolower ($this->ContentType)=='xml' )
		{
			header("Content-type: text/xml");
		}
		global $Result;
		$url="https://secure.payonlinesystem.com/payment/transaction/rebill/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&RebillAnchor=".$this->RebillAnchor."&OrderId=".$this->OrderId."&Amount=".$this->Amount."&Currency=".$this->Currency."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&RebillAnchor=".urlencode($this->RebillAnchor)."&OrderId=".urlencode($this->OrderId)."&Amount=".$this->Amount."&Currency=".$this->Currency."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="REBILL: Неуказан один из параметров TransactionId,Amount или RebillAnchor";
		}
	//CURL
	 $url = $Result;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	return $Result;
	}
	
	
	
// Метод void получение ссылки
	function GetPaymentVoidURL()
	{
	if ($this->TransactionId)
		{
	
		$url="https://secure.payonlinesystem.com/payment/transaction/void/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="VOID: Неуказан параметр TransactionId ";
		}
	
		return $Result;
	}
//метод ребил с получением результатов	(CURL)
	function PaymentVoid()
	{
	if ($this->TransactionId )
		{
			if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
		global $Result;
		$url="https://secure.payonlinesystem.com/payment/transaction/void/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="VOID: Неуказан параметр TransactionId ";
		}
	//CURL
	 $url = $Result;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	return $Result;
	}
	
	
	
// Метод refund получение ссылки
	function GetPaymentRefundURL()
	{
	if ($this->TransactionId AND $this->Amount)
		{
	
		$url="https://secure.payonlinesystem.com/payment/transaction/refund/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	echo $Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="Refund: Неуказан параметр TransactionId или Amount";
		}
	
		return $Result;
	}
//метод Refund с получением результатов	
	function PaymentRefund()
	{
	if ($this->TransactionId AND $this->Amount)
		{
		if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
		global $Result;
		$url="https://secure.payonlinesystem.com/payment/transaction/refund/";
	 	$queryComplete="MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&PrivateSecurityKey=".$this->PrivateSecurityKey;
		$SecurityKey=md5($queryComplete);
	 	$Result=$url."?MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&Amount=".$this->Amount."&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
		}
		else 
		{
		$Result="Refund: Неуказан параметр TransactionId или Amount";
		}
	//CURL
	 $url = $Result;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	return $Result;
	}
	

	
	// Метод auth 
		function PaymentAuth()
	{
	if ($this->Ip AND $this->CardHolderName AND $this->CardNumber AND $this->CardExpDate AND $this->CardCvv AND $this->Issuer AND $this->OrderId AND $this->Amount AND $this->Currency)
	{
	$params	 = 'MerchantId='. $this->MerchantId;
	$params .= '&OrderId='.$this->OrderId;
	$params .= '&Amount='. $this->Amount;
	$params .= '&Currency='. $this->Currency;
	$params .= '&PrivateSecurityKey=' . $this->PrivateSecurityKey;
		if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
	//узнаем ключ
	global $Result;
	$SecurityKey=md5($params);
	$Paymenturl="https://secure.payonlinesystem.com/payment/transaction/auth/";
	$url_query= "MerchantId=".$this->MerchantId."&OrderId=".$this->OrderId."&Amount=".$this->Amount."&Currency=".$this->Currency."&Ip=".$this->Ip."&CardHolderName=".$this->CardHolderName."&CardNumber=".$this->CardNumber.
	"&CardExpDate=".$this->CardExpDate."&CardCvv=".$this->CardCvv."&Issuer=".$this->Issuer."&Email=".$this->Email;

	if ($this->Country AND strlen($this->Country)==2) {$url_query.= "&Country=".$this->Country;}
	if ($this->City) {$url_query.= "&City=".$this->City;} 
	if ($this->Address) {$url_query.= "&Address=".$this->Address;} 
	if ($this->Zip) {$url_query.= "&Zip=".$this->Zip;} 	
	if ($this->State) {$url_query.= "&State=".$this->State;} 
	if ($this->Phone) {$url_query.= "&Phone=".$this->Phone;} 
	
	 $url_query.="&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
	

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $Paymenturl); 
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $url_query);
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	
	}
	else {echo $Result="AUTH : Нет одного из обязательного параметра";}
	
	return $Result;
	}
	
	
	
	// Метод 3DS 
		function Payment3DS()
	{
	if ($this->PD AND $this->PARes AND $this->TransactionId)
	{
	$params	 = 'MerchantId='. $this->MerchantId;
	$params .= '&TransactionId='.$this->TransactionId;
	$params .= '&PARes='. $this->PARes;
	$params .= '&PD='. $this->PD;
	$params .= '&PrivateSecurityKey=' . $this->PrivateSecurityKey;
	//узнаем ключ
	$SecurityKey=md5($params);
	global $Result;
		if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
	$Paymenturl="https://secure.payonlinesystem.com/payment/transaction/auth/3ds/";
	$url_query= "MerchantId=".$this->MerchantId."&TransactionId=".$this->TransactionId."&PARes=".$this->PARes."&PD=".$this->PD;
	$url_query.="&SecurityKey=".$SecurityKey."&ContentType=".$this->ContentType;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $Paymenturl); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $url_query);
    $Result = curl_exec($ch); 
    curl_close($ch); 
    echo $Result ; 
	
	}
	else {echo $Result="3DS: Нет одного из обязательного параметра";}
	
	return $Result;
	}
	
	// Метод auth && 3DS 
		function PaymentAuth3DS()
	{
	
	if ($this->Ip AND $this->CardHolderName AND $this->CardNumber AND $this->CardExpDate AND $this->CardCvv AND $this->Issuer AND $this->TermUrl)
	{
	$params	 = 'MerchantId='. $this->MerchantId;
	$params .= '&OrderId='.$this->OrderId;
	$params .= '&Amount='. $this->Amount;
	$params .= '&Currency='. $this->Currency;

	$params .= '&PrivateSecurityKey=' . $this->PrivateSecurityKey;
	//узнаем ключ
		if (strtolower ($this->ContentType)=='xml' )
		{
		header("Content-type: text/xml");
		}
	$SecurityKey=md5($params);
	$Paymenturl="https://secure.payonlinesystem.com/payment/transaction/auth/";
 	$url_query= "MerchantId=".$this->MerchantId."&OrderId=".$this->OrderId."&Amount=".$this->Amount."&Currency=".$this->Currency."&Ip=".$this->Ip."&CardHolderName=".$this->CardHolderName."&CardNumber=".$this->CardNumber.
	"&CardExpDate=".$this->CardExpDate."&CardCvv=".$this->CardCvv."&Issuer=".$this->Issuer."&Email=".$this->Email."&ContentType=".$this->ContentType;
	if ($this->ValidUntil) {$url_query.= "&ValidUntil=".$this->ValidUntil;} 
	if ($this->OrderDescription) {$url_query.= "&OrderDescription=".$this->OrderDescription;} 
	if ($this->Country AND strlen($this->Country)==2) {$url_query.= "&Country=".$this->Country;}
	if ($this->City) {$url_query.= "&City=".$this->City;} 
	if ($this->Address) {$url_query.= "&Address=".$this->Address;} 
	if ($this->Zip) {$url_query.= "&Zip=".$this->Zip;} 	
	if ($this->State) {$url_query.= "&State=".$this->State;} 
	if ($this->Phone) {$url_query.= "&Phone=".$this->Phone;} 
	
	 $url_query.="&SecurityKey=".$SecurityKey;


	 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $Paymenturl); 
    //For Debugging 
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $url_query);
	$Result = curl_exec($ch); 
    curl_close($ch); 
	
	//для дебага	
	/*$f2=fopen("xml.txt", "a");
	   fwrite($f2,$Result);
	 fclose($f2);
	 */
	//Парсим XML ответ
	$xml = simplexml_load_string($Result);
	$xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
	//print_r($xml_array);
	//Формируем данные для отправки на стр.банка эмитента
	$pd=$xml_array['threedSecure']['pd'];
	$site_banka=$xml_array['threedSecure']['acsurl'];
	$pareq=$xml_array['threedSecure']['pareq'];
	$MD=$xml_array['id'].";".$pd;
	$termurl=$this->TermUrl;
	
	 $data="PaReq=".$pareq."&MD=".$MD."&TermUrl=".$termurl;
	
    
	 
	if (($pd!='' OR $site_banka!='') AND strlen($pd)>40)
	{
	header("Content-type: text/html");
	
	echo	$site="
	<script type=\"text/javascript\">
    function myfunc () {
        var frm = document.getElementById(\"pareq\");
        frm.submit();
    }
    window.onload = myfunc;
</script>
<form method='post' action='".$site_banka."' id='pareq'>
<input type='hidden' name='PaReq'
value='".$pareq."' />
<input type='hidden' name='TermUrl' value='".$this->TermUrl."' />
<input type='hidden' name='MD'value='".$MD."' />

</form>";

	} else {echo $Result;}

	}
	else {echo $Result="Auth3DS: Нет одного из обязательного параметра";}

	return  $Result;
	}
	
}


?>