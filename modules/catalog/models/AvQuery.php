<?php
namespace app\modules\catalog\models;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 18.06.15
 * Time: 16:36
 */
class AvQuery extends \yii\db\Query

{
    public static $db;
    public static $image;
    public $url_params=[];
    public static $pref='';

    public function __construct($params){
        parent::__construct();
        $this->setUrlParams($params);
    }
    public function getFields()
    {
        return ['name'];
    }

    public function setUrlParams($params)
    {
//        var_dump($params);die;
        $this->url_params=array_merge($this->url_params,$params);
//        var_dump($this->url_params);die;
//        $fields=$this->getFields();
//        foreach ($fields as $f){
//            $this->url_params[$f]=(isset($params[$f])) ? self::__get($params[$f]) : '';
//            $this->url_params[$f]=(isset($params[$f])) ? $params[$f] : '';
//        }
    }

//    public function getUrlAction($action)
//    {
////        $vars=get_class_vars(self::class);
//        return Url::to(array_merge([$action],$this->url_params));
//    }

    public function getParamSelect()
    {
        $select='';
        $p=self::$pref;
//        var_dump($this->url_params);die;
        foreach($this->url_params as $key=>$val){
            $select.=($val!='')?", '$val' $p$key":'';
        }
        return $select;
    }
    public function createCommand($db = null)
    {

        if ($db === null) {
            $db = \Yii::createObject(self::$db);
        }
        list ($sql, $params) = $db->getQueryBuilder()->build($this);
        $sql=str_replace('FROM',$this->getParamSelect().' FROM',$sql);
        return $db->createCommand($sql, $params);
    }

    public static function getConnectParam()
    {
        $connect_param = ['dsn'=>self::$db['dsn'],
            'username' => self::$db['username'],
            'password' => self::$db['password'],
            'charset' => self::$db['charset']];
        return $connect_param;
    }

    public static function getImageUrl()
    {
        return self::$image;
    }




}