<?php

ini_set("soap.wsdl_cache_enabled", "0");


// Create the SoapClient instance
$params=array(
'host' => 'http://ws.auto-iksora.ru/',
'uri' => 'http://tempuri.org/',
'login' => 'brnosn', //'BRINEXNCH', 	//пользователь по договору
'password' => 'ditx6bny',	//'e4du7aps',  	//пароль
'contractid' => "91001", 	//номер контракта
'marga'=>'1.15',			//надбавка к цене
);          

$nodes = array(
    array('field'=>'maker', 'title'=>'Производитель','enable'=>true,'column'=>1,
		'items'=>array(
            		array('field'=>'id', 'title'=>'код','enable'=>false,),
            		array('field'=>'name', 'title'=>'название','enable'=>true,)
		),
	
    	),
    array('field'=>'detailnumber', 'title'=>'Номер детали','enable'=>true, ),
    array('field'=>'detailname', 'title'=>'Наименование','enable'=>true,'option'=>'class="part-name"',),
    array('field'=>'quantity', 'title'=>'Наличие (шт.)','enable'=>true,),
    array('field'=>'lotquantity', 'title'=>'Заказ от (шт.)','enable'=>true,'option'=>'title="Минимальная партия заказа по которой действует цена на товар"'),
    array('field'=>'dayswarranty', 'title'=>'Срок доставки','enable'=>true,'column'=>2,),
    array('field'=>'estimation', 'enable'=>true,),
    array('field'=>'price', 'title'=>'Цена','enable'=>true,'style'=>'',),
    array('field'=>'ball', 'title'=>'Баллы','enable'=>true,'option'=>'class="part-bonus" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки."',),
    array('field'=>'pricedestination', 'title'=>'Источник цен','enable'=>false,),
    array('field'=>'days', 'title'=>'Срок доставки','enable'=>false,),
    array('field'=>'regionname', 'title'=>'Регион','enable'=>false,),
    array('field'=>'orderrefernce','title'=> 'orderrefernce','enable'=>false,),
    array('field'=>'pricedate','title'=> 'Дата цены','enable'=>false,),
    array('field'=>'groupid','title'=> 'Группа','enable'=>false,),
);


class iksora

{
    public $contractid;
    public $detail;
    public $HTML;
    public $location;
    public $uri;
    private $host;
    private $login;
    private $password;
    private $xml;
    private $nodes;
    private $marga;	

    function __construct($nodes,$p)
    {
        $this->host = $p['host']; // "http://ws.auto-iksora.ru/";
        $this->login = $p['login'];
        $this->password = $p['password'];
        $this->xml = new DOMDocument("1.0", "utf-8");
        $this->nodes = $nodes;
        $this->contractid = $p['contractid'];
        $this->uri = $p['uri']; //"http://tempuri.org/";
	$this->marga =$p['marga'];
    }
public function replace()
{
$details = simplexml_import_dom($this->xml);
for ($i=0;$i<count($details->DetailInfo);++$i){
$detail=$details->DetailInfo[$i];
//$detail=$this->repRating($detail);
$detail=$this->repBalls($detail);
$details->DetailInfo[$i]=$detail;
}
$this->xml->loadXML($details->asXML());
}
	private function Balls()
	{
	$details = simplexml_import_dom($this->xml);
	for ($i=0;$i<count($details->DetailInfo);++$i){
	$price=$details->DetailInfo[$i]->price;
	$details->DetailInfo[$i]->addChild('ball',floor($price*0.05));
	}
	$this->xml->loadXML($details->asXML());
	}

	private function PlusMarga()
	{
	$details = simplexml_import_dom($this->xml);
	for ($i=0;$i<count($details->DetailInfo);++$i){
	$price=$details->DetailInfo[$i]->price*$this->marga;	
	$details->DetailInfo[$i]->price=$price;
	}

	$this->xml->loadXML($details->asXML());

	}
	public function repBalls($detail){
$price=$detail->price;
$detail->addChild('ball',floor($price*0.05));
return $detail;
}
	public function repRating($detail){
	$nval=$detail->estimation;
	$summa=0;
	$count=0;
		$nval=trim($nval);
		$n=strlen($nval);
		for($i=0;$i<$n;$i++){
		$b=is_numeric($nval[$i]);
		$summa+=($b)?$nval[$i]:0;
		$count+=($b)?1:0;
		}

		$estimation=round(($count>0)?($summa/$count)*20:0,0);
		$detail->estimation=$estimation;		
	return $detail;	
	}
    public function GetFieldHttp($ap)
    {
        $response = '';
        foreach ($ap as $key => $value) {
            $response .= $key . '=' . $value . '&';
        }
        return $response;
    }
    public function FindDetails($np)
    {
        $p = array('DetailNumber' => $np,
            'ContractID' => $this->contractid,
            'Login' => $this->login,
            'Password' => $this->password,
            'MakerID' => 0,
        );
        $response = $this->getPost('FindDetails', $p);

	if (strpos($response,'<DetailInfo>')===false){ 
$response='<?xml version="1.0" encoding="utf-8"?>
<ArrayOfDetailInfo xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://tempuri.org/">
  <DetailInfo>
    <detailnumber>'.$np.'</detailnumber>
    <detailname>Заказать запчасть</detailname>
    <maker>
      <id>-</id>
      <name>NA</name>
    </maker>
    <quantity>0</quantity>
    <lotquantity>-</lotquantity>
    <price>0</price>
    <pricedestination>0</pricedestination>
    <days>-</days>
    <dayswarranty>-</dayswarranty>
    <regionname>-</regionname>
    <estimation>- - -</estimation>
    <orderrefernce></orderrefernce>
    <pricedate></pricedate>
    <groupid></groupid>
  </DetailInfo>
</ArrayOfDetailInfo>';}
	
        $this->xml->loadXML($response);
	$this->PlusMarga();
	$this->Balls();
	//$this->replace();
        return $response;

    }

    private function getPost($fname, $p)
    {
        $PostCurl = curl_init();
        $goo = $this->host . "/searchdetails/searchdetails.asmx/" . $fname;

        curl_setopt_array($PostCurl, array(
            CURLOPT_URL => $goo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->GetFieldHttp($p),
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $response = curl_exec($PostCurl);
        return $response;
    }

    private function getGet($fname, $p)
    {
        $PostCurl = curl_init();
        $goo = $this->host . "/searchdetails/searchdetails.asmx/" . $fname;
        curl_setopt_array($PostCurl, array(
            CURLOPT_URL => $goo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => false,
            CURLOPT_POSTFIELDS => http_build_query($p),
        ));
        $response = curl_exec($PostCurl);
        curl_close($PostCurl);
        return $response;
    }

    public function GetDetailInfo()
    {
        return $this->xml->getElementsByTagName('DetailInfo');
    }


}

