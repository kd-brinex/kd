<?php

namespace app\modules\autoparts\models;

use app\modules\user\models\Orders;
use Yii;

/**
 * This is the model class for table "provider_state_code".
 *
 * @property integer $provider_id
 * @property integer $status_code
 * @property string $status_name
 *
 * @property Orders[] $orders
 */
class ProviderStateCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider_state_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider_id', 'status_code'], 'required'],
            [['provider_id', 'status_code'], 'integer'],
            [['status_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'provider_id' => 'Provider ID',
            'status_code' => 'Status Code',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['provider_id' => 'provider_id', 'order_provider_status' => 'status_code']);
    }
}
