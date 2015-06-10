<?php

namespace app\modules\autoparts\models;

use Yii;
use app\modules\city\models\City;

/**
 * This is the model class for table "part_provider_srok".
 *
 * @property integer $provider_id
 * @property integer $city_id
 * @property integer $days
 *
 * @property PartProvider $provider
 * @property GeobaseCity $city
 */
class PartProviderSrok extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_provider_srok';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider_id', 'city_id', 'days', 'id'], 'integer'],
            [['days'], 'required'],
            [['provider_id', 'city_id'], 'unique', 'targetAttribute' => ['provider_id', 'city_id'], 'message' => 'The combination of Provider ID and City ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'provider_id' => 'Provider ID',
            'city_id' => 'City ID',
            'days' => 'Days',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(PartProvider::className(), ['id' => 'provider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

}
