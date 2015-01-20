<?php

namespace app\modules\city\models;

use Yii;

/**
 * This is the model class for table "geobase_city".
 *
 * @property string $id
 * @property string $name
 * @property string $region_id
 * @property double $latitude
 * @property double $longitude
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geobase_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'region_id', 'latitude', 'longitude','enable'], 'required'],
            [['id', 'region_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['enable'],'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Город',
            'region_id' => 'Код региона',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
            'enable'=>'Использовать',
        ];
    }
}
