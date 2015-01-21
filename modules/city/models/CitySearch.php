<?php

namespace app\modules\city\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\city\models\City;

/**
 * CitySearch represents the model behind the search form about `app\modules\city\models\City`.
 */
class CitySearch extends City
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function citylist($params)
    {
        $query = City::findBySql('
select
  c.id as id,
  c.name as city,
  r.name as region,
  st_distance((select point from geobase_city where name=:city), c.point) dist
from geobase_city as c
  left join geobase_region as r on r.id=c.region_id
order by dist asc' . ((isset($params['limit'])) ? ' limit :limit' : '') . ';', $params)->query();
        return $query;
    }
}
