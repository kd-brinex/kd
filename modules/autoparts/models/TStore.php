<?php

namespace app\modules\autoparts\models;

use Yii;
use app\modules\city\models\City;

/**
 * This is the model class for table "t_store".
 *
 * @property integer $id
 * @property string $name
 * @property string $addr
 * @property string $tel
 * @property integer $city_id
 *
 * @property PartProviderUser[] $partProviderUsers
 * @property GeobaseCity $city
 */
class TStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'city_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['addr'], 'string', 'max' => 200],
            [['tel'], 'string', 'max' => 15],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'addr' => 'Addr',
            'tel' => 'Tel',
            'city_id' => 'City ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartProviderUsers()
    {
        return $this->hasMany(PartProviderUser::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }




}
