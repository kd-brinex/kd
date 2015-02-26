<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.02.15
 * Time: 14:26
 */
namespace app\modules\basket\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
class BasketSearch extends Basket{
    public $tovarname;
    public $tovar_summa;
    public $phpsessid;
    public function rules()
    {
        return [
            [['tovar_count', 'tovar_min','id'], 'integer'],
//            [['tovarname'], 'string'],
            [['tovar_price', 'session_id'], 'required'],
            [['tovar_price'], 'number'],
            [['tovar_id'], 'string', 'max' => 9],
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
        $c=Yii::$app->session;
        $c->open();
        $params['session_id']=$c->id;
        $query = Basket::find();
        $query->andWhere('session_id=:session_id');
        $query->addParams($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        return $dataProvider;
    }
}