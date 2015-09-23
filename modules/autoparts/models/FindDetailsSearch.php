<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 15.09.15
 * @time: 13:28
 */
namespace app\modules\autoparts\models;

use yii\data\ActiveDataProvider;

class FindDetailsSearch extends FindDetails{

    public function search($params){

        if(!empty($stores = $this->getStores())){
            foreach($stores as $store){
                $stores_id[] = $store['id'];
            }
        }

        $query = self::find()
            ->andWhere('detailnumber = :detailnumber')
            ->andWhere(['storeid' => $stores_id])
            ->addParams($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        if(!$this->validate())
            return $dataProvider;

        return $dataProvider;
    }

    //Эти методы временно здесь.
    private function getStores(){
        if(!empty(($city = $this->getCityId())))
            $store = TStore::find()
                ->select('id')
                ->asArray()
                ->where('city_id = :city_id', [':city_id' => $city])
                ->all();

        return !empty($store) ? $store : 109;
    }

    private function getCityId(){
        if (!empty(($cookie = \Yii::$app->request->cookies['city'])))
        {$cityId = (int)$cookie->value;}
        else
        {$cityId = 1751;}
        return $cityId;
    }
    //////////////////////////
}