<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:42
 */

namespace app\modules\autoparts\providers;



class Iksora extends PartsProvider
{
    public $contractid = "91001";
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



    public static function nameProvider()
    {
        return 'Нижний Новгород';
    }
    public function getData()
    {
        $data = parent::getData();
        $defaults = array(
            'detailnumber' => (isset($data['article']))?$data['article']:$this->article,
            'makerid' => '',
            'contractid' => '91001',
//            'Login' => $this->login,
//            'Password' => $this->password,
        );
        $data = array_merge($defaults, $data);
        return $data;
    }

    public function xmlFindDetailsXML()
    {
        $data = $this->getData();
//        var_dump($data);die;
        $xml = ['DetailNumber'=> $data['detailnumber'],
                'MakerID>'=>$data['makerid'],
                'ContractID'=>$data['contractid'],
                'Login'=>$data['login'],
                'Password'=>$data['password']];
//    </FindDetails>';

//       return array('FindDetails' =>$xml);
        return $xml;
    }

    public function getResultXML($result,$method){
        $result=parent::getResultXML($result,$method);
        return $result->any;
    }
    public function parseSearchResponseXML($xml) {
        $data = array();
        foreach($xml->row as $row) {
            $_row = array();
            foreach($row as $key => $field) {
                $_row[(string)$key] = (string)$field;
            }
            $data[] = $_row;
        }
        return $data;
    }
    public function update_estimation($value){
        $summa=0;
        $count=0;
        $nval=trim($value['estimation']);
        $n=strlen($nval);
        for($с=0;$с<$n;$с++){
            $b=is_numeric($nval[$с]);
            $summa+=($b)?$nval[$с]:0;
            $count+=($b)?1:0;
        }

        $estimation=round(($count>0)?($summa/$count)*20:0,0);
        return $estimation;
    }
    /*
    public function setProperties($aProperties){
        foreach ($aProperties as $name => $value){
            $this->$name=$value;
        }
    }
    public function Balls()
    {

        $details = simplexml_import_dom($this->xml);
        for ($i = 0; $i < count($details->DetailInfo); ++$i) {
            $price = $details->DetailInfo[$i]->price;
            $details->DetailInfo[$i]->addChild('ball', floor($price * 0.05));
        }
        $this->xml=$details;
    }

       public function PlusMarga()
       {

           $details = simplexml_import_dom($this->xml);
           for ($i = 0; $i < count($details->DetailInfo); ++$i) {
               $price = $details->DetailInfo[$i]->price * $this->marga;
               $details->DetailInfo[$i]->price = $price;
           }

           $s->xml=$details;

       }

           public function GetFieldHttp($ap)
           {
               $response = '';
               foreach ($ap as $key => $value) {
                   $response .= $key . '=' . $value . '&';
               }
              eturn $response;
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

               return $this->xml_to_array($this->xml->saveXML());
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
           }*/
}