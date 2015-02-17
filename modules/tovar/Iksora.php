<?php
namespace app\modules\tovar;

//ini_set("soap.wsdl_cache_enabled", "0");


// Create the SoapClient instance
//use yii\base\Model;

//$params=array(
//'host' => 'http://ws.auto-iksora.ru/',
//'uri' => 'http://tempuri.org/',
//'login' => 'brnosn', //'BRINEXNCH', 	//пользователь по договору
//'password' => 'ditx6bny',	//'e4du7aps',  	//пароль
//'contractid' => "91001", 	//номер контракта
//'marga'=>'1.15',			//надбавка к цене
//);
//
//$nodes = array(
//    array('field'=>'maker', 'title'=>'Производитель','enable'=>true,'column'=>1,
//		'items'=>array(
//            		array('field'=>'id', 'title'=>'код','enable'=>false,),
//            		array('field'=>'name', 'title'=>'название','enable'=>true,)
//		),
//
//    	),
//    array('field'=>'detailnumber', 'title'=>'Номер детали','enable'=>true, ),
//    array('field'=>'detailname', 'title'=>'Наименование','enable'=>true,'option'=>'class="part-name"',),
//    array('field'=>'quantity', 'title'=>'Наличие (шт.)','enable'=>true,),
//    array('field'=>'lotquantity', 'title'=>'Заказ от (шт.)','enable'=>true,'option'=>'title="Минимальная партия заказа по которой действует цена на товар"'),
//    array('field'=>'dayswarranty', 'title'=>'Срок доставки','enable'=>true,'column'=>2,),
//    array('field'=>'estimation', 'enable'=>true,),
//    array('field'=>'price', 'title'=>'Цена','enable'=>true,'style'=>'',),
//    array('field'=>'ball', 'title'=>'Баллы','enable'=>true,'option'=>'class="part-bonus" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки."',),
//    array('field'=>'pricedestination', 'title'=>'Источник цен','enable'=>false,),
//    array('field'=>'days', 'title'=>'Срок доставки','enable'=>false,),
//    array('field'=>'regionname', 'title'=>'Регион','enable'=>false,),
//    array('field'=>'orderrefernce','title'=> 'orderrefernce','enable'=>false,),
//    array('field'=>'pricedate','title'=> 'Дата цены','enable'=>false,),
//    array('field'=>'groupid','title'=> 'Группа','enable'=>false,),
//);


class Iksora
{
    public $contractid = "91001";
    public $detail;
    public $HTML;
    public $location;
    public $uri = 'http://tempuri.org/';
    private $host = 'http://ws.auto-iksora.ru/';
    private $login = 'brnosn';
    private $password = 'ditx6bny';
    private $xml;
    public $nodes = [
        ['field' => 'maker', 'title' => 'Производитель', 'enable' => true, 'column' => 1,
            'items' => [
                ['field' => 'id', 'title' => 'код', 'enable' => false,],
                ['field' => 'name', 'title' => 'название', 'enable' => true,]
            ],

        ],
        ['field' => 'detailnumber', 'title' => 'Номер детали', 'enable' => true,],
        ['field' => 'detailname', 'title' => 'Наименование', 'enable' => true, 'option' => 'class="part-name"',],
        ['field' => 'quantity', 'title' => 'Наличие (шт.)', 'enable' => true,],
        ['field' => 'lotquantity', 'title' => 'Заказ от (шт.)', 'enable' => true, 'option' => 'title="Минимальная партия заказа по которой действует цена на товар"'],
        ['field' => 'dayswarranty', 'title' => 'Срок доставки', 'enable' => true, 'column' => 2,],
        ['field' => 'estimation', 'enable' => true,],
        ['field' => 'price', 'title' => 'Цена', 'enable' => true, 'style' => '',],
        ['field' => 'ball', 'title' => 'Баллы', 'enable' => true, 'option' => 'class="part-bonus" title="Количество начисляемых баллов. Баллы начисляются при покупке товара через сайт! Начисленные баллы становятся активными по истечении 14 дней с момента покупки."',],
        ['field' => 'pricedestination', 'title' => 'Источник цен', 'enable' => false,],
        ['field' => 'days', 'title' => 'Срок доставки', 'enable' => false,],
        ['field' => 'regionname', 'title' => 'Регион', 'enable' => false,],
        ['field' => 'orderrefernce', 'title' => 'orderrefernce', 'enable' => false,],
        ['field' => 'pricedate', 'title' => 'Дата цены', 'enable' => false,],
        ['field' => 'groupid', 'title' => 'Группа', 'enable' => false,],
    ];
    private $marga = 1.15;

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function __construct()
    {
//        $this->host = $p['host']; // "http://ws.auto-iksora.ru/";
//        $this->login = $p['login'];
//        $this->password = $p['password'];
//        $this->xml = new DOMDocument("1.0", "utf-8");
//        $this->nodes = $nodes;
//        $this->contractid = $p['contractid'];
//        $this->uri = 'http://tempuri.org/'; //"http://tempuri.org/";
//	$this->marga =1.15;
    }

    private function Balls()
    {
        $details = simplexml_import_dom($this->xml);
        for ($i = 0; $i < count($details->DetailInfo); ++$i) {
            $price = $details->DetailInfo[$i]->price;
            $details->DetailInfo[$i]->addChild('ball', floor($price * 0.05));
        }
        $this->xml->loadXML($details->asXML());
    }

    private function PlusMarga()
    {
        $details = simplexml_import_dom($this->xml);
        for ($i = 0; $i < count($details->DetailInfo); ++$i) {
            $price = $details->DetailInfo[$i]->price * $this->marga;
            $details->DetailInfo[$i]->price = $price;
        }

        $this->xml->loadXML($details->asXML());

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

        if (strpos($response, '<DetailInfo>') === false) {
            $response = '<?xml version="1.0" encoding="utf-8"?>
<ArrayOfDetailInfo xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://tempuri.org/">
  <DetailInfo>
    <detailnumber>' . $np . '</detailnumber>
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
    <orderrefernce>-</orderrefernce>
    <pricedate>-</pricedate>
    <groupid>-</groupid>
  </DetailInfo>

</ArrayOfDetailInfo>';
        }

        $this->xml = $response;
//	$this->PlusMarga();
//	$this->Balls();
//        return $response;

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

    public function asArray()
    {
        return $this->xml_to_array($this->xml);
    }

    private function xml_to_array($xml)
    {
        $XML = trim($xml);

//var_dump($XML);
// Expand empty tags
        $emptyTag = '<(.*) \/>';
        $fullTag = '<\1>0</\1>';
        $XML = preg_replace("|$emptyTag|", $fullTag, $XML);
        $XML = preg_replace("|<\?(.*)\?>|", "", $XML);
        $XML = preg_replace("|<ArrayOfDetailInfo(.*)>|", "", $XML);
        $matches = [];
        $XML = trim($XML);
        $returnVal = $XML;

        $i = preg_match_all('|<(.*)>(.*)<\/\1>|Ums', $XML, $matches);

        if ($i) {
            if (count($matches[1]) > 0) $returnVal = [];
            foreach ($matches[1] as $index => $outerXML) {
                $attribute = $outerXML;
                $value = $this->xml_to_array($matches[2][$index]);
                if (!isset($returnVal[$attribute])) $returnVal[$attribute] = [];
                $returnVal[$attribute][] = $value;
            }
        }

        if (is_array($returnVal)) foreach ($returnVal as $key => $value) {
            if (is_array($value) && count($value) == 1 && key($value) === 0) {
                $returnVal[$key] = $returnVal[$key][0];
            }
        }
        return $returnVal;
    }

    public function asXML()
    {
        return $this->xml;
    }

    public function val_node($nname, $detail)
    {

        $nval = (isset($detail[$nname])) ? $detail[$nname] : 0;
// Округление суммы
        if ($nname == 'price') {
            $rval = round($nval);
            $nval = ($rval > $nval) ? $rval : $rval + 1;
        }

// название+код
        if ($nname == 'detailname') {
            $nval .= '(' . $detail['detailnumber'] . ')';
        }
        return $nval;
    }

    public function html_node($nname, $node)
    {
        $color = 'black';
        $summa = 0;
        $count = 0;
//        var_dump($node);die;
        $nval = isset($node[$nname]) ? $node[$nname] : 0;
// Округление суммы
        if ($nname == 'price') {
            $rval = round($nval);
            $nval = ($rval > $nval) ? $rval : $rval + 1;
        }

//Надежность поставщика ввиде знака
        if ($nname == 'estimation') {
            $nval = trim($nval);
            $n = strlen($nval);
            for ($i = 0; $i < $n; $i++) {
                $b = is_numeric($nval[$i]);
                $summa += ($b) ? $nval[$i] : 0;
                $count += ($b) ? 1 : 0;
            }

            $estimation = round(($count > 0) ? ($summa / $count) * 20 : 0, 0);
            $e = ($estimation <= 85) ? 'good' : 'fine';
            $e = ($estimation <= 25) ? 'bad' : $e;
            $nval = '<div class="square ' . $e . '" title="' . 'Надежность поставщика' . ' ' . $estimation . '%" ></div>';
        }

//Выделение цветом для партии
        if ($nname == 'lotquantity') {
            $color = ($nval > 1) ? 'red' : $color;

        }

        $nval = '<span style="color:' . $color . '">' . $nval . '</span>';
        return $nval;
    }

    public function t($text)
    {
        return iconv("WINDOWS-1251", "UTF-8", $text);
    }

    public function tag_node($detail, $nodes, $tag)
    {
        $r = ['tag' => '', 'val' => ''];
        foreach ($nodes as $node) {

            if (isset($node['items'])) {

                $t = $this->tag_node($detail[$node['field']], $node['items'], 'td');
                $r['tag'] .= $t['tag'];
                $r['val'] .= '"' . $node['field'] . '":[{' . substr($t['val'], 0, -1) . '}],';

            } else {
                $v = $this->val_node($node['field'], $detail);
                $h = $this->html_node($node['field'], $detail);
                if ($node['enable']) {
                    $option = (isset($node['option'])) ? $node['option'] : '';
                    $r['tag'] .= '<' . $tag . ' ' . $option . ' name="' . $node['field'] . '">' . $h . '</' . $tag . '>';
                }

                $r['val'] .= '"' . $node['field'] . '":"' . $v . '",';
            }

        }
        return $r;
    }
}

