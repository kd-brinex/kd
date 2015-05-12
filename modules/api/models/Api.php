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
        $dxml="";
        foreach($details as $d){
            $dxml.='<DetailInfo>
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
<storeid>'.$d['storeid'].'</storeid>
<FlagPostav>'.$d['flagpostav'].'</FlagPostav>
</DetailInfo>';
}
        if ($dxml==""){
//            var_dump($params);die;
            $dxml='<DetailInfo>
<detailnumber>'.$params["article"].'</detailnumber>
<detailname></detailname>
<maker>
<id>0</id>
<name>0</name>
</maker>
<quantity>0</quantity>
<lotquantity>0</lotquantity>
<price>0</price>
<pricedestination>0</pricedestination>
<days>999</days>
<dayswarranty>999</dayswarranty>
<regionname></regionname>
<estimation></estimation>
<orderrefernce></orderrefernce>
<pricedate></pricedate>
<groupid></groupid>
<provider></provider>
<storeid>'.$params["store_id"].'</storeid>
<FlagPostav></FlagPostav>
</DetailInfo>';
        }
        $xml.=$dxml.'
</ArrayOfDetailInfo>';
return $xml;
    }
}