<?php

namespace app\modules\autoparts\models;

use Yii;

/**
 * This is the model class for table "part_provider".
 *
 * @property integer $id
 * @property string $name
 *
 * @property PartProviderUser[] $partProviderUsers
 */
class PartProvider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 15],
            [['name'], 'unique'],
            [['weight','enable','cross'],'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Внутренний код',
            'name' => 'Название',
            'enable' => 'Включить',
            'weight' => 'Вес',
            'flagpostav' => 'Флаг поставщика для 1C',
            'cross' => 'Кроссы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartProviderUsers()
    {
        return $this->hasMany(PartProviderUser::className(), ['provider_id' => 'id']);
    }

}
