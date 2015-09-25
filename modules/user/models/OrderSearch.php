<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 09.09.15
 * @time: 12:54
 */
namespace app\modules\user\models;

use Yii;
use yii\data\ActiveDataProvider;

class OrderSearch extends Order{

    public function search($condition = '', $params = [], $with = ''){

        $query = self::find();
//            ->andWhere($condition)
//            ->addParams($params)
//            ->orderBy('date DESC');

        $query->joinWith(['store', 'orders']);

        if(!empty($with))
            $query->with($with);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $dataProvider->sort->attributes['store_name'] = [
            'asc' => ['t_store.name' => SORT_ASC],
            'desc' => ['t_store.name' => SORT_DESC]
        ];

        //ОСТОРОЖНО! КАСТЫЛЬ!
        $dataProvider->sort->attributes['managerOrderStatus'] = [
            'asc' => ['orders.status' => SORT_ASC],
            'desc' => ['orders.status' => SORT_DESC]
        ];

        $this->load($params);
        if(!$this->validate())
            return $dataProvider;

        $query->andFilterWhere(['like', 'date', $this->date])
              ->andFilterWhere(['like', 't_store.name', $this->store_name])
              ->andFilterWhere(['like', 'user_name', $this->user_name])
              ->andFilterWhere(['like', '1c_order_id', $this->{'1c_order_id'}])
              ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}