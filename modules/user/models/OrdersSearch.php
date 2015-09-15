<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\modules\basket\models\Order`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'status'], 'integer'],
            [['product_id', 'reference', 'datetime'], 'safe'],
            [['part_name'],'string']

        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($condition = '', $params = [])
    {

        $query = self::find()
            ->andWhere($condition)
            ->addParams($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        $query->andFilterWhere([
//            'id' => $this->id,
//            'uid' => $this->uid,
//            'quantity' => $this->quantity,
//            'status' => $this->status,
//            'datetime' => $this->datetime,
//            'part_name' => $this->part_name,
//        ]);
//
//        $query->andFilterWhere(['like', 'product_id', $this->product_id])
//            ->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
