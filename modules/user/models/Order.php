<?php

namespace app\modules\user\models;

use app\modules\autoparts\models\TStore;
use app\modules\city\City;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $number
 * @property string $date
 * @property integer $user_id
 *
 * @property User $user
 * @property OrderPay[] $orderPays
 * @property Orders[] $orders
 */
class Order extends \yii\db\ActiveRecord
{
    public $store_name;
//    public $managerOrderStatus;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'store_name'], 'safe'],
            [['id','user_id'], 'integer'],
            [['store_id'], 'integer'],
            [['number'], 'string', 'max' => 15],
            [['1c_order_id', 'comment'], 'string'],
            [['user_name','user_email','user_telephone','user_location'], 'string', 'max'=>25],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'date' => 'Date',
            'user_id' => 'User ID',
            'managerOrderStatus' => 'Статус'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPays()
    {
        return $this->hasMany(OrderPay::className(), ['order_id' => 'id']);
    }

    public function getOrderPaysSum(){
        return $this->hasMany(OrderPay::className(), ['order_id' => 'id'])->sum('sum');
    }

    public function getManagerOrderStatus(){
        $executed = 0;
        $execution_step = floor(100 / count($this->orders));
        foreach($this->orders as $order){
            if($order->status > \app\modules\user\models\Orders::ORDER_ADOPTED)
                $executed += $execution_step;
        }
        return $executed;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['order_id' => 'id']);
    }

    public function getOrderSumma()
    {
        return $this->hasMany(Orders::className(), ['order_id' => 'id'])->sum('part_price * quantity');
    }

    public function getDescriptionPay()
    {
        return $this->user_name;
    }

    public function getStore(){
        return $this->hasOne(TStore::className(), ['id' => 'store_id']);
    }

}
