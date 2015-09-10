<?php

namespace app\modules\images\models;

use Yii;

/**
 * This is the model class for table "img_image".
 *
 * @property integer $id
 * @property string $table
 * @property integer $table_id
 * @property string $src
 * @property string $title
 * @property string $alt
 */
class ImgImage extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'img_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_id'], 'integer'],
            [['table', 'src', 'title', 'alt'], 'string', 'max' => 255]
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table' => 'Таблица',
            'table_id' => 'ID таблицы',
            'src' => 'Превью',
            'title' => 'Title',
            'alt' => 'Alt',
        ];
    }
}
