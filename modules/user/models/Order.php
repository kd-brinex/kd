<?php

namespace app\modules\user\models;

use Faker\Provider\DateTime;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $product_id
 * @property integer $quantity
 * @property string $reference
 * @property integer $status
 * @property string $datetime
 *
 * @property Tovar $product
 * @property User $u
 */
class Order extends \yii\db\ActiveRecord
{
    public $normalizeDate;

    const JUST_ORDERED = 1;
    const DENIED = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'status', 'datetime', 'part_price'], 'required', 'except' => 'update'],
            [['uid', 'quantity', 'status', 'store_id'], 'integer'],
            [['datetime'], 'safe'],
            [['pay_datetime'], 'safe'],
            [['product_id'], 'string', 'max' => 9],
            [['reference'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID заказа',
            'uid' => 'Пользватель',
            'product_id' => 'ID товара',
            'quantity' => 'Количество',
            'reference' => 'Reference',
            'status' => 'Статус',
            'datetime' => 'Дата заказа',
            'pay_datetime' => 'Дата платежа'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\app\modules\tovar\models\TovarSearch::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserData()
    {
        return $this->hasOne(\app\modules\user\models\Profile::className(), ['user_id' => 'uid']);
    }

    public function getState(){
        return $this->hasOne(OrderState::className(), ['id' => 'status']);
    }

    public function getStore(){
        return $this->hasOne(\app\modules\autoparts\models\TStoreSearch::className(),['id' => 'store_id']);
    }

    public function getProvider(){
        return $this->hasOne(\app\modules\autoparts\models\PartProviderSearch::className(), ['id' => 'provider_id']);
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->status = self::JUST_ORDERED;
            $this->datetime = new \yii\db\Expression('NOW()');
        }

//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('Y-m-d H:i:s');

        $date = new \DateTime($this->datetime);
        $this->datetime = $date->format('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
    public function afterSave($insert, $changedAttributes){
//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('d.m.Y H:i');
    }
    public function afterFind(){
//        $date = new \DateTime($this->pay_datetime);
//        $this->pay_datetime = $date->format('d.m.Y H:i');

        $date = new \DateTime($this->datetime);
        $this->datetime = $date->format('d.m.Y H:i');
    }

}
