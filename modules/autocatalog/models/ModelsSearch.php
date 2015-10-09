<?php
namespace app\modules\autocatalog\models;

use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 12:47
 */

class ModelsSearch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['region','family','from'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'region' => 'Регион',
        ];
    }

    public static function tableName()
    {
        return 'v_models';
    }

    public function search($params=[])
    {
        $query =parent::find()->where('family=:family',[':family'=>$params['family']]);
        return $query;
    }
}