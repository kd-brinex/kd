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

    public function search($params = []){
        $params = [
            ':uid' => Yii::$app->user->id
        ];
        $query = self::find()
            ->andWhere('user_id = :uid')
            ->addParams($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if($this->load($params) && !$this->validate())
            return $dataProvider;


        return $dataProvider;

    }
}