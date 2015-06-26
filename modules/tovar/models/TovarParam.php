<?php

namespace app\modules\tovar\models;

use Yii;

/**
 * This is the model class for table "tovar_param".
 *
 * @property string $id
 * @property string $tip_id
 * @property string $category_id
 * @property string $name
 * @property string $param_id
 * @property string $value_char
 * @property integer $value_int
 * @property double $value_float
 * @property integer $store_id
 * @property double $price
 * @property integer $count
 * @property string $title
 * @property string $pname
 * @property integer $ball
 * @property string $description
 */
class TovarParam extends Tovar
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tovar_param';
    }

    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['id', 'tip_id', 'category_id', 'name'], 'required'],
//            [['value_int', 'store_id', 'count', 'ball'], 'integer'],
//            [['value_float', 'price'], 'number'],
//            [['id', 'category_id'], 'string', 'max' => 9],
//            [['tip_id'], 'string', 'max' => 25],
//            [['name'], 'string', 'max' => 200],
//            [['param_id'], 'string', 'max' => 50],
//            [['value_char', 'title', 'pname'], 'string', 'max' => 45],
//            [['description'], 'string', 'max' => 500]
//        ];
//    }

    /**
     * @inheritdoc
     */

}
