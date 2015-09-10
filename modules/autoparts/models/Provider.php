<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 14:54
 */

namespace app\modules\autoparts\models;


use yii\db\ActiveRecord;

class Provider extends ActiveRecord
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
            [['name','flagpostav'], 'string', 'max' => 15],
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