<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "order_states".
 *
 * @property integer $id
 * @property string $status_name
 */
class OrderState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_name'], 'required'],
            [['id'], 'integer'],
            [['status_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_name' => 'Status Name',
        ];
    }
}
