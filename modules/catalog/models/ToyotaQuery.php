<?php
namespace app\modules\catalog\models;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.06.15
 * Time: 10:12
 */
class ToyotaQuery extends AvQuery
{
    public $name = "TOYOTA";
    public $model_name;
    public $catalog;
    public $catalog_code;
    public $model_code;
    public $sysopt;
    public $compl_code;
    public $part_group;

    public function getUrlParams($action){
//        $vars=get_class_vars(self::class);

     $params=[
         'name'=>$this->name,
         'model_name'=>$this->model_name,
         'catalog'=>$this->catalog,
         'catalog_code'=>$this->catalog_code,
         'model_code'=>$this->model_code,
         'sysopt'=>$this->sysopt,
         'compl_code'=>$this->compl_code,
         'part_group'=>$this->part_group,
     ];
        return Url::to($action,$params);
    }

}