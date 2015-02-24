<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.02.15
 * Time: 14:26
 */
namespace app\modules\basket\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
class BasketSearch extends Basket{
    public $tovarname;
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
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Basket::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        return $dataProvider;
    }
}