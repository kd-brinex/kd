<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "order_pay".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $date
 * @property double $sum
 *
 * @property Order $order
 */
class OrderPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['date'], 'safe'],
            [['sum'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'date' => 'Date',
            'sum' => 'Sum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
