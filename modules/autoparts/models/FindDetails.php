<?php

namespace app\modules\autoparts\models;

use Yii;

/**
 * This is the model class for table "finddetails".
 *
 * @property string $id
 * @property string $detailname
 * @property string $detailnumber
 * @property double $price
 * @property integer $quantity
 * @property integer $storeid
 * @property integer $srokmax
 * @property integer $estimation
 * @property integer $groupid
 */
class FindDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finddetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['id', 'detailname'], 'required'],
            [['price'], 'number'],
            [['quantity', 'storeid', 'srokmax', 'estimation', 'groupid'], 'integer'],
            [['id'], 'string', 'max' => 9],
            [['detailname'], 'string', 'max' => 200],
            [['detailnumber'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detailname' => 'Detailname',
            'detailnumber' => 'Detailnumber',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'storeid' => 'Storeid',
            'srokmax' => 'Srokmax',
            'estimation' => 'Estimation',
            'groupid' => 'Groupid',
        ];
    }
}
