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
<detailname>'.$d['name'].'</detailname>
<maker>
<id>-</id>
<name>'.$d['manufacture'].'</name>
</maker>
<quantity>'.$d['quantity'].'</quantity>
<lotquantity>'.$d['lotquantity'].'</lotquantity>
<price>'.$d['price'].'</price>
<pricedestination>'.$d['pricedestination'].'</pricedestination>
<days>'.$d['srokmin'].'</days>
<dayswarranty>'.$d['srokmax'].'</dayswarranty>
<regionname>'.$d['sklad'].'</regionname>
<estimation>'.$d['estimation'].'</estimation>
<orderrefernce>'.$d['reference'].'</orderrefernce>
<pricedate>'.$d['pricedate'].'</pricedate>
<groupid>'.$d['groupid'].'</groupid>
<provider>'.$d['provider'].'</provider>
</DetailInfo>';
}
        $xml.='
</ArrayOfDetailInfo>';
return $xml;
    }
}