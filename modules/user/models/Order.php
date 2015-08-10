<?php

namespace app\modules\user\models;

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

    const JUST_ORDERED = 1;
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
            [['product_id', 'quantity', 'status', 'datetime', 'part_price'], 'required'],
            [['uid', 'quantity', 'status', 'store_id'], 'integer'],
            [['datetime'], 'safe'],
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

    public function beforeInsert(){
        if($this->isNewRecord){
            $this->status = self::JUST_ORDERED;
            $this->datetime = new \yii\db\Expression('NOW');
        }
        return parent::beforeValidate();
    }
}
