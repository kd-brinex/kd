<?php

namespace app\modules\loader\models;

use Yii;

/**
 * This is the model class for table "1c_load".
 *
 * @property resource $blob_data
 * @property integer $id
 */
class Loader extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '1c_load';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blob_data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blob_data' => 'Blob Data',
            'id' => 'ID',
        ];
    }

}
