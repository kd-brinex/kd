<?php

namespace app\modules\api\models;

use Yii;
use yii\base\Model;
use app\modules\tovar\models\Tovar;

class Api extends Model
{
    public static function finddetails($params){
        $details=Tovar::findDetails($params);
        $xml='<?xml version="1.0" encoding="utf-8"?>
<ArrayOfDetailInfo>';
        foreach($details as $d){
            $xml.='<DetailInfo>
<detailnumber>'.$d['code'].'</detailnumber>
<detailname>'.htmlspecialchars($d['name'],ENT_XML1).'</detailname>
<maker>
<id>-</id>
<name>'.htmlspecialchars($d['manufacture'],ENT_XML1).'</name>
</maker>
<quantity>'.$d['quantity'].'</quantity>
<lotquantity>'.$d['lotquantity'].'</lotquantity>
<price>'.$d['price'].'</price>
<pricedestination>'.$d['pricedestination'].'</pricedestination>
<days>'.$d['srokmin'].'</days>
<dayswarranty>'.$d['srokmax'].'</dayswarranty>
<regionname>'.htmlspecialchars($d['sklad'],ENT_XML1).'</regionname>
<estimation>'.$d['estimation'].'</estimation>
<orderrefernce>'.$d['reference'].'</orderrefernce>
<pricedate>'.$d['pricedate'].'</pricedate>
<groupid>'.$d['groupid'].'</groupid>
<provider>'.$d['provider'].'</provider>
<pid>'.$d['pid'].'</pid>
<storeid>'.$d['storeid'].'</storeid>
<FlagPostav>'.$d['flagpostav'].'</FlagPostav>
</DetailInfo>';
}
        $xml.='
</ArrayOfDetailInfo>';
return $xml;
    }

    /**
     * findtovar - Поиск товара
     * @param $params -
     * id - код товара в 1с (ЦО)
     * tip_id - тип товара
     * category_id - категория товара
     * store_id - номер магазина
     *
     *
     */
    public static function findtovars($params)
    {
//        var_dump($params);die;
        return  Tovar::find()->andWhere($params)->asArray()->all();
    }
}