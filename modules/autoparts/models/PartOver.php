<?php

namespace app\modules\autoparts\models;

use Yii;
use app\modules\city\models\City;


/**
 * This is the model class for table "part_over".
 *
 * @property integer $code
 * @property string $name
 * @property string $manufacture
 * @property double $price
 * @property integer $quantity
 * @property integer $srokmin
 * @property integer $srokmax
 * @property integer $lotquantity
 * @property string $pricedate
 * @property integer $skladid
 * @property string $sklad
 * @property string $flagpostav
 */
class PartOver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_over';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['price'], 'number'],
            [['quantity', 'srokmin', 'srokmax', 'lotquantity', 'skladid','cityid'], 'integer'],
            [['date_update'], 'safe'],
            [['code','name', 'manufacture', 'sklad', 'flagpostav'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Артикул',
            'name' => 'Название',
            'manufacture' => 'Manufacture',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'srokmin' => 'Srokmin',
            'srokmax' => 'Srokmax',
            'lotquantity' => 'Lotquantity',
            'pricedate' => 'Pricedate',
            'skladid' => 'Skladid',
            'sklad' => 'Sklad',
            'flagpostav' => 'Код поставщика',
            'date_update' => 'Дата обновления',
            'cityid' => 'Код города',
            'cityname' => 'Город',

        ];
    }
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityid']);
    }

    public function getCityname()
    {
        return (isset($this->city)) ? $this->city->name : ' ';

    }





}
