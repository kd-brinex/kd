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
            [['id', 'tip_id', 'category_id', 'name', 'price', 'count', 'value_char', 'param_id'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id', 'param_id'], 'string', 'max' => 25],
            [['name', 'value_char'], 'string', 'max' => 200],
            [['price', 'count'], 'int']
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
            'price' => 'Цена',
            'count' => 'Кол.',
            'value_char' => 'Значение',
            'image' => 'Изображение',
            'param_id' => 'Характеристика',
            'bigimage' => 'Изображение',
        ];
    }

    public function getImage()
    {
//        var_dump($this);die;

        $p = Yii::$app->params;
//        var_dump( $this[$p['image'][$this->tip_id]['name']]);die;

        return (isset($p['image'][$this->tip_id])) ?
            $p['host'] . $p['image'][$this->tip_id]['normal'] .   $this[$p['image'][$this->tip_id]['name']]. '.jpg':
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getBigimage()
    {
        $p = Yii::$app->params;
        return (isset($p['image'][$this->tip_id])) ?
            $p['host'] . $p['image'][$this->tip_id]['big'] .   $this->category_id . '.jpg':
            'http://img2.kolesa-darom.ru/img/' . $this->tip_id . '/' . $this->category_id . '.jpg';
    }

    public function getSrok()
    {
        return ($this->count > 0) ? '<span class="offer-v1-deliv-instock">✓В наличии</span>' : '<span class="offer-v1-deliv-days">• Доставка 3-5 дней</span>';
    }

}
