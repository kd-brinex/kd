<?php

namespace app\modules\basket\models;

//use app\modules\tovar\models\TovarSearch;

use Yii;
use app\modules\tovar\models\Tovar;
use yii\db\Query;

//use app\modules\tovar\models\TovarSearch;
/**
 * This is the model class for table "basket".
 *
 * @property integer $id
 * @property string $tovar_id
 * @property integer $tovar_count
 * @property integer $tovar_min
 * @property resource $tovar
 * @property double $tovar_price
 * @property string $session_id
 */
class Basket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return 'basket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tovar_count', 'tovar_min','id'], 'integer'],
//            [['tovarname'], 'string'],
            [['tovar_price', 'session_id'], 'required'],
            [['tovar_price'], 'number'],
            [['tovar_id'], 'string', 'max' => 9],
            [['session_id'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tovar_id' => 'Tovar ID',
            'tovar_count' => 'Tovar Count',
            'tovar_min' => 'Tovar Min',
            'tovar' => 'Tovar',
            'tovar_summa' =>'Стоимость',
            'tovar_price' => 'Tovar Price',
            'session_id' => 'Session ID',
        ];
    }

    public function getTovar()
    {
        $t=$this->hasOne(Tovar::className(), ['id' => 'tovar_id']);

        return $t;
    }

    public function getTovarname()
    {
//        var_dump($this->tovars);die;

        return  (isset($this->tovar->name))?$this->tovar->name:'tovar';
    }
    public function getTovar_summa()
    {
        return $this->tovar_count*$this->tovar_price;
    }
    private function getPhpsessid()
    {

        $c=Yii::$app->session;
        return $c->id;

    }
    public function put($params)
    {
//        var_dump($params);die;
        $model=Tovar::findOne(['id'=>$params['id']]);
//        var_dump($model);die;
        $t=$model->attributes;
//        var_dump($t);die;
        $basket = Yii::$app->getDb();

        $default= ['session_id' => $this->getPhpsessid(),'tovar_count'=>1,'tovar_min'=>1,'tovar_id'=>$t['id'],'tovar_price'=>$t['price']];
//        var_dump($params);die;
        $params=array_merge($default,$params);
//            var_dump($params);die;

        if ($params['tovar_count']>0) {
        return $basket->createCommand('insert into `basket` (`tovar_id`,`tovar_count`,`tovar_min`,`tovar_price`,`session_id`)'
        . 'values(:tovar_id,:tovar_count,:tovar_min,:tovar_price,:session_id)'
        .' ON DUPLICATE KEY UPDATE `tovar_count`=:tovar_count')->bindValues($params)->execute();
        }
    else
        {
//            var_dump($params);die;
            $p = ['session_id' => $params['session_id'], 'tovar_id' => $params['tovar_id']];
           return $basket->createCommand('delete from `basket` where `session_id`=:session_id and `tovar_id`=:tovar_id')->bindValues($p)->execute();
        }
    }
    public static function find(){
        $c=Yii::$app->session;
        $params['session_id']=$c->id;
        $query = parent::find();
        $query->andWhere('session_id=:session_id');
        $query->addParams($params);
        return $query;
    }

}
