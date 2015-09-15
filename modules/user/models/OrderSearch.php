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

        $query = self::find()
            ->andWhere($condition)
            ->addParams($params)
        ->orderBy('date desc');

        if(!empty($with))
            $query->with($with);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        if(!$this->validate())
            return $dataProvider;

        return $dataProvider;
    }
}