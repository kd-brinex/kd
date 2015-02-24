<?php

namespace app\modules\basket\models;

use Yii;
use app\modules\tovar\models\TovarSearch;
/**
 * This is the model class for table "basket".
 *
 * @property integer $id
 * @property string $tovar_id
 * @property integer $tovar_count
 * @property integer $tovar_min
 * @property resource $tovar
 * @property double $tovar_price
 * @property string $session_id
 */
class Basket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    public static function tableName()
    {
        return 'basket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tovar_count', 'tovar_min'], 'integer'],
            [['tovarname'], 'string'],
            [['tovar_price', 'session_id'], 'required'],
            [['tovar_price'], 'number'],
            [['tovar_id'], 'string', 'max' => 25],
            [['session_id'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tovar_id' => 'Tovar ID',
            'tovar_count' => 'Tovar Count',
            'tovar_min' => 'Tovar Min',
            'tovar' => 'Tovar',
            'tovarname' =>'Tovar',
            'tovar_price' => 'Tovar Price',
            'session_id' => 'Session ID',
        ];
    }

    public function getTovars()
    {
        return $this->hasOne(TovarSearch::className(), ['id' => 'tovar_id']);
    }

    public function getTovarname()
    {
        return $this->tovars->name;
    }
}
