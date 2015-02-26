<?php

namespace app\modules\basket\models;

use Yii;
use app\modules\tovar\models\TovarSearch;
use yii\data\ActiveDataProvider;
use app\modules\basket\models\Basket;

/**
 * This is the model class for table "zakaz".
 *
 * @property integer $id
 * @property string $session
 * @property string $user_id
 * @property string $user_name
 * @property string $user_telephon
 * @property string $user_email
 * @property integer $pay_id
 * @property integer $store_id
 * @property string $adr_city
 * @property string $adr_adres
 * @property string $adr_index
 * @property resource $zakaz
 * @property double $zakaz_summa
 * @property string $zakaz_date
 */
class Zakaz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $phpsessid;
    public $_basket;

    public static function tableName()
    {
        return 'zakaz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'session_id', 'user_name', 'user_telephon', 'pay_id', 'store_id'], 'required'],
            [['id', 'pay_id', 'store_id'], 'integer'],
            [['zakaz'], 'string'],
            [['zakaz_summa'], 'number'],
            [['zakaz_date'], 'safe'],
            [['session_id', 'user_id', 'user_name', 'user_telephon', 'user_email', 'adr_city'], 'string', 'max' => 45],
            [['adr_adres'], 'string', 'max' => 150],
            [['adr_index'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_telephon' => 'User Telephon',
            'user_email' => 'User Email',
            'pay_id' => 'Pay ID',
            'store_id' => 'Store ID',
            'adr_city' => 'Adr City',
            'adr_adres' => 'Adr Adres',
            'adr_index' => 'Adr Index',
            'zakaz' => 'Zakaz',
            'zakaz_summa' => 'Zakaz Summa',
            'zakaz_date' => 'Zakaz Date',
        ];
    }
//    public function put($params)
//    {
//
//        $tovar=new TovarSearch();
//        $model=$tovar->findOne($params);
//        $basket = Yii::$app->getDb();
//        $params= ['session_id' => $this->getPhpsessid(),'tovar_count'=>1,'tovar_min'=>1,'tovar_id'=>$model->id,'tovar_price'=>$model->price];
//        $basket->createCommand('insert into basket (tovar_id,tovar_count,tovar_min,tovar,tovar_price,session_id)'
//            . 'values(:tovar_id,:tovar_count,:tovar_min,null,:tovar_price,:session_id)')->bindValues($params)->execute();
//
//    }

    private function getPhpsessid()
    {

        $c=Yii::$app->session;
        $c->open();
                return $c->id;

    }
    public function getBasket()
    {
        if(!isset($this->_basket)) {
            $query = new BasketSearch();
//        $query->find()->leftJoin('tovar',['tovar.id'=>'tovar_id'])->where(['session_id'=>$this->getPhpsessid()]);
        $this->_basket= $query->search([]);
        }
        return $this->_basket;
    }


}
