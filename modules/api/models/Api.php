<?php

namespace app\modules\api\models;

use app\modules\autocatalog\models\ActiveRecord;
use app\modules\autoparts\models\TStore;
use app\modules\tovar\models\TovarSearch;
use Yii;
use yii\base\Model;
use app\modules\tovar\models\Tovar;
use app\modules\tovar\models\TTovar;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Api extends Model
{

    public static function finddetails($params){
        $p = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        $params['store_id']=(!empty($p['store_id']))?$p['store_id']:109;
        $tstore=new TStore();
        $store=$tstore->findOne(['id'=> $params['store_id']]);
        $params['city_id']=$store->city_id;
//        var_dump($params);die;
        $details=Tovar::findDetails($params);
        $xml='<?xml version="1.0" encoding="utf-8"?>
<ArrayOfDetailInfo>';
        foreach($details as $d){
            $xml.='<DetailInfo>
<detailnumber>'.$d['code'].'</detailnumber>
<detailname>'.htmlspecialchars($d['name'],ENT_XML1).'</detailname>
<dname>'.htmlspecialchars($d['name'],ENT_XML1).'</dname>
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
<srok>'.$d['srok'].'</srok>
<ball>'.$d['ball'].'</ball>
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
    public static function tovar_tip($params)
    {
//
        if (!isset($params['tip_id'])){return json_encode(['error'=>$params]);}
    $tovars=new TovarSearch();
//        var_dump($params);
    $fields=\Yii::$app->params['Api']['tovar_tip'];
//  var_dump($fields);die;
    $dp= $tovars->category_list($params);
//        var_dump($dp->pagination->offset,$dp->pagination->limit);die;
        if (isset($params['page'])){$dp->pagination->setPage($params['page']);}
        if (isset($params['pagesize'])){$dp->pagination->setPageSize($params['pagesize']);}
        foreach($dp->models as $model){
            foreach($fields as $f){
                $ret['response'][$model->id][$f]=$model->$f;
            }
        }
//        $db->
        $ret['header']=['totatCount'=>$dp->totalCount];
//        var_dump(json_encode($ret)));die;
        return json_encode($ret);
    }

    public static function tovar($params){
        if (!isset($params['id'])){return json_encode(['error'=>$params]);}
        $tovar=new TovarSearch();
        $dp=$tovar->find_tovar_param($params);
        $ret=[];
        if ($dp->count>0){
        foreach($dp->models as $model){
        $ret['params'][$model['param_id']]=[
            'param_id'=>$model['param_id'],
            'value'=>$model['value_char'],
            'title'=>$model['title'],
            'name'=>$model['pname'],
        ];
        }
        $ret['tovar']['id']=$model['id'];
        $ret['tovar']['tip_id']=$model['tip_id'];
        $ret['tovar']['category_id']=$model['category_id'];
        $ret['tovar']['name']=$model['name'];
        $ret['tovar']['description']=$model['description'];
        $ret['tovar']['price']=$model['price'];
        $ret['tovar']['store_id']=$model['store_id'];}


        return json_encode($ret);
    }
    public static function ttovar_tip($params)
    {
//        var_dump($params);die;
        if (!isset($params['tip_id'])){return json_encode(['error'=>$params]);}
        $tovars=new TTovar();
        $dp= $tovars->search_tip_id($params);
//        var_dump($dp);die;
        foreach($dp->models as $model){
                $ret['response'][$model['id']]=$model;
        }
        $ret['header']=['totalCount'=>$dp->totalCount];
//        var_dump($ret,json_encode($ret));die;
        return json_encode($ret);
    }
    public static function ttovar_tip_id_list()
    {
        $tovars=new TTovar();
        $models=$tovars->search_all();
        return ArrayHelper::map($models, 'tip_id', 'tip_id');

    }
    public static function tparam_list($params)
    {
        $tovars=new TTovar();
        $models=$tovars->search_params($params);
        return $models;
//        return ArrayHelper::map($models, 'id', 'name');
    }
    public static function getUrl_ttovar_tip($params){
        $a=['ttovar_tip'];
        $a['tip_id']=$params['tip_id'];
        $a['page']=$params['page'];
        $a['page_size']=$params['page_size'];
        $a['store_id']=$params['store_id'];
        $a['where']=$params['where'];
        $a['orderby']=$params['orderby'];
        $s='{';
//        var_dump($params['options']);die;
        foreach($params['options'] as $key=>$val)
        {
            $s.='"'.$key.'":"'.$val.'",';
//            if (!empty($val)){$s.='"'.$key.'":"'.$val.'",';}
        }
        $s=($s!='{')?substr($s,0,-1):$s;
        $s.='}';
        $a['options']=$s;
        $url=Url::toRoute($a);
        return $url;

    }
    public static function loader()
    {
        $model = new ActiveRecord();
        $model->find()
        ->select("*")
        ->from("v_loader")->all();
        $provider = new ActiveDataProvider(['query'=>$model]);
        return $provider;

    }
}