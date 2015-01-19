<?php

namespace app\modules\tovar\models;

use Yii;

/**
 * This is the model class for table "t_tovar".
 *
 * @property string $id
 * @property string $tip_id
 * @property string $category_id
 * @property string $name
 */
class Tovar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tovar';
    }

    /**
     * @inheritdoc
     */
//SELECT `tovar`.`id`,
//`tovar`.`tip_id`,
//`tovar`.`category_id`,
//`tovar`.`name`,
//`tovar`.`param_id`,
//`tovar`.`value_char`,
//`tovar`.`value_int`,
//`tovar`.`value_float`,
//`tovar`.`id_store`,
//`tovar`.`price`,
//`tovar`.`count`
//FROM `brinex1`.`tovar`;

    public function rules()
    {
        return [
            [['id', 'tip_id', 'category_id', 'name','price','count','value_char','param_id'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id','param_id'], 'string', 'max' => 25],
            [['name','value_char'], 'string', 'max' => 200],
            [['price','count'],'int']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tip_id' => 'Tip ID',
            'category_id' => 'Category ID',
            'name' => 'Наименование',
            'price'=>'Цена',
            'count'=>'Кол.',
            'value_char'=>'Значение',
            'image'=>'Изображение',
            'param_id'=>'Характеристика',
            'bigimage'=>'Изображение',
        ];
    }
    public function getImage()
    {
//        http://img2.kolesa-darom.ru/img/disk/big/CO19594SPL.jpg
        return 'http://img2.kolesa-darom.ru/img/'.$this->tip_id.'/'.$this->id.'.jpg';
    }
    public function getBigimage()
    {
//        http://img2.kolesa-darom.ru/img/disk/big/CO19594SPL.jpg
        return 'http://img2.kolesa-darom.ru/img/'.$this->tip_id.'/big/'.$this->id.'.jpg';
    }

}
