<?php

namespace app\modules\autoparts\models;
use yii\data\ActiveDataProvider;

class TStoreSearch extends TStore{
    public $cityname;
    public function searchCity(){
        $query = TStore::find()->groupBy('city_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);




        return $dataProvider;
    }
    public function search()
    {
        $query = TStore::find();

//$query=$this->find_tovar_param($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($params,$this->validate(),$this->load($params));die;




        return $dataProvider;
    }

    public function getCityList(){

       $h =  $this->searchCity();
        $mas=[];
        foreach ($h->models as $n){
            $mas[$n->city_id] = $n->cityname;
        }
    return $mas;
    }
}