<?php
Yii::$app->view->registerCssFile('/css/parts.css');
?>

<div class="center-td">
<div class="sel-parts borders">
<div class="sel-parts-1">
<h2>Найти запчасть<br>по номеру детали</h2>
<br>
<form name="search" method="GET" action="">
<input type="text" class="bootstrap-input" name="article" id="article" >
<input type="submit" class="btn" value="Найти">
</form>
</div>
<div class="sel-parts-2">
<h2>Номер детали<br>можно узнать в каталогах</h2>
<br>
<a class="btn" href="http://www.kolesa-darom.ru/auto-parts/autocatalog/">Открыть каталог автомобилей </a>
</div>
<div class="clearfix"></div>
</div>
</div>

<?php

if (isset($params['article'])) {

    $finddetail= '<div style="width: 100%;"><table id="tableparts" class="tablesorter">';
    $finddetail.= '<thead><tr>';
    foreach ($mod->nodes as $node) {
//    $style= ' style="' . $node['style'] .'"';
        if (isset($node['column'])) {
            $colspan = ' colspan="' . $node['column'] . '"';
        } else {
            $colspan = '';
        }
        if ($node['enable'] and (isset($node['title']))){$finddetail.= '<th' . $colspan .'>' . $node['title'] . '</th>';}

    }
    $finddetail.= '<th></th></tr></thead><tbody>';
    $ir=0;
//    var_dump($details);die;
    $detailn=$params['article'];
    $details= (isset($details['DetailInfo'][0]))?$details['DetailInfo']:[0=>$details['DetailInfo']];
//            var_dump($details);die;
    foreach ($details as $detail) {
        $ir=$ir+1;
        $finddetail.= '<tr id="'.$detailn.'_'.$ir.'">';
//        var_dump($detail);die;
        $tag= $mod->tag_node($detail, $mod->nodes, 'td');
        $finddetail.=$tag["tag"];
        // $finddetail.='<td><a class="btn btn-order">'.t("заказать").'</a></td>';
        $son='{'.substr($tag['val'],0,-1).'}';
        $val=json_decode($son);
//$lotquantity=$detail->getElementsByTagName('lotquantity')->item(0)->nodeValue;
        $price=$detail['price'];
//$detailnumber=$detail->getElementsByTagName('detailnumber')->item(0)->nodeValue;
//var_dump($val);
        $id_part=uniqid('50_',true);
        $idhash=base64_encode(json_encode($val));
//var_dump( $detail->getElementsByTagName('detailname')->item(0)->nodeValue);
        if ($price==0)
        {$finddetail.='<td class="product-order"><a class="btn btn-order" href="http://www.kolesa-darom.ru/auto-parts/buy/?f_Kol=1&f_Message= Запчасть артикул: '.$val->detailnumber.'">заказать</a>';}
        else
        {$finddetail.='<td class="product-order"><a class="btn btn-order" href="javascript:inbasket(\''.$idhash.'\',\''.$id_part.'\','.$val->lotquantity.');">заказать</a>';}
        $finddetail.='<input id="'.$id_part.'" class="basket-cnt" size="4" value="0" onchange="basketInputControlActivate(\''.$idhash.'\',111)" type="hidden">';
//        $finddetail.='<span class="basket-price" id="'.$id_part.'"></span>';
//        $finddetail.='<div class="basket-price" id="'.$id_part.'Price">'.$val->price.'</div>';
//        $finddetail.='<div class="basket-price" id="'.$id_part.'Sel">50</div></td>';

        $finddetail.= '</tr>';
    };
    $finddetail.= '</tbody></table></div>
<div id="response"></div>';

echo '<p align="center">Срок доставки указан до центрального склада.Заказ уходит в работу после 100% оплаты.</p>
<table>
<tr><td><div class="square fine"></div></td><td> - товар придет в полном объеме и в срок</td></tr>
<tr><td><div class="square good"></div></td><td> - возможно изменение цены и задержка срока поставки</td></tr>
<tr><td><div class="square bad"></div></td><td> - возможен отказ в поставке или изменение цены</td></tr>
</table>';
} else {
    $finddetail= '';
}

echo $finddetail;

