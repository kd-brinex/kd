<?php

namespace app\modules\site\tovar\models;

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
            [['id', 'tip_id', 'category_id', 'name','price','count','value_char'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id'], 'string', 'max' => 25],
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
            'name' => 'Name',
            'price'=>'Цена',
            'count'=>'Кол.',
            'value_char'=>'value_char',
        ];
    }

}
