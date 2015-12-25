<?php

namespace app\modules\user\models;

use app\modules\autoparts\models\PartProvider;
use app\modules\autoparts\models\ProviderStateCode;
use Yii;

/**
 * This is the model class for table "state_relation".
 *
 * @property integer $inner_state_id
 * @property integer $provider_id
 * @property integer $provider_state_id
 *
 * @property ProviderStateCode $provider
 * @property OrderStates $innerState
 * @property PartProvider $provider0
 */
class OrderStateRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inner_state_id', 'provider_id', 'provider_state_id'], 'required'],
            [['inner_state_id', 'provider_id', 'provider_state_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inner_state_id' => 'Inner State ID',
            'provider_id' => 'Provider ID',
            'provider_state_id' => 'Provider State ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(ProviderStateCode::className(), ['provider_id' => 'provider_id', 'status_code' => 'provider_state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInnerState()
    {
        return $this->hasOne(OrdersState::className(), ['id' => 'inner_state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider0()
    {
        return $this->hasOne(PartProvider::className(), ['id' => 'provider_id']);
    }
}
