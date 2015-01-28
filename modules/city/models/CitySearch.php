<?php

namespace app\modules\city\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\city\models\City;
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
    public $dist;


//    public $start;


    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name'], 'safe'],
            [['latitude', 'longitude','dist'], 'number'],
            [['enable'], 'boolean'],
            [['point'],'safe'],
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
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'enable' => $this->enable,
            'point'=>$this->point,
            'regionName'=>$this->regionName,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $query;
    }
    public function find_list($param){
    $city = City::findOne(['id'=>$param['id']]);
        $param[':lat']=$city->attributes['latitude'];
        $param[':long']=$city->attributes['longitude'];
        $query=Yii::$app->db->createCommand('select
  c.id as id,
  c.name as name,
 (  6371 * acos(
    cos(radians(:lat)) * cos(radians(c.latitude)) * cos(radians(c.longitude) - radians(:long))
    +
    sin(radians(:lat)) * sin(radians(c.latitude))
  )
) AS dist
from geobase_city as c
  left join geobase_region as r on r.id=c.region_id
  where c.enable=true
  order by dist ')->bindValues($param)->queryAll();

        return $query;
    }


}
