<?php
namespace app\modules\autocatalog\models;



/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 12:47
 */

class CarsSearch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
        ];
    }

    public static function tableName()
    {
        return 'v_cars';
    }

    public static function search()
    {
        return self::find();
    }

    public function getUrl()
    {
        return \yii\helpers\Url::to($this->region.'/'.$this->family);
    }
}