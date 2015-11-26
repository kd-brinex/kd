<?php

namespace app\modules\autoparts\models;

use Yii;

/**
 * This is the model class for table "order_update_1c".
 *
 * @property integer $order_id
 *
 * @property Order $order
 */
class OrderUpdate1c extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OrderUpdate1c';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'OrderId' => 'Order ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'OrderId']);
    }
}
