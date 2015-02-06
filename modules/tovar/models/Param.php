<?php

namespace app\modules\tovar\models;

use Yii;

/**
 * This is the model class for table "t_param".
 *
 * @property string $id
 * @property string $name
 * @property string $title
 */
class Param extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 50],
            [['name', 'title'], 'string', 'max' => 45],
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
            'title' => 'Title',
        ];
    }
}
