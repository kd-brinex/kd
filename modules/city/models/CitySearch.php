<?php

namespace app\modules\city\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\city\models\City;
use app\modules\city\models\Region;
use yii\db\ActiveRecord;

/**
 * CitySearch represents the model behind the search form about `app\modules\city\models\City`.
 */
class CitySearch extends City
{
    /**
     * @inheritdoc
     */
    public $regionName;
//    public $dist;


//    public $start;


    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['enable'], 'boolean'],
//            [['point'],'safe'],
            [['regionName'],'safe'],


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

    public function search($params)
    {
        $query = City::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'enable' => $this->enable,

//            'point'=>$this->point,

        ]);



        return $dataProvider;
    }
    public static function find_list(){
    $query['stories'] = City::find()->select('geobase_city.name,geobase_city.id')
        ->leftJoin('t_store','t_store.city_id=geobase_city.id')
            ->where('t_store.id IS NOT NULL')->orderBy('geobase_city.name')->asArray()->all();
    $query['stories_all'] = City::find()->leftJoin('t_store','t_store.city_id=geobase_city.id')
        ->where('t_store.id IS NULL')->orderBy('geobase_city.name')->asArray()->all();
        //echo '<pre>';print_r($query['stories_all']);echo '</pre>';die;
  $query['regions'] = Region::find()->asArray()->orderBy('geobase_region.name')->all();

        return $query;



    }
    public function find_list_region($params){


        $query['stories'] = City::find()->leftJoin('t_store','geobase_city.id=t_store.city_id')->where(['region_id'=>$params['id']])->andwhere('t_store.id IS NOT NULL')->asArray()->all();
        $query['delivery'] = City::find()->leftJoin('t_store','geobase_city.id=t_store.city_id')->where(['region_id'=>$params['id']])->andwhere(['geobase_city.enable'=>'1'])->andwhere('t_store.id IS  NULL')->asArray()->all();


        return $query;



    }


}
