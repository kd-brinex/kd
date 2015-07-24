<?php

namespace app\modules\autoparts\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * PartProviderSearch represents the model behind the search form about `app\modules\autoparts\models\PartProvider`.
 */
class PartProviderSrokSearch extends PartProviderSrok
{
    /**
     * @inheritdoc
     */
    public $providername;
    public $cityname;
    public function rules()
    {
        return[

            [['provider_id', 'city_id','days'], 'integer'],

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
        $query = PartProviderSrok::find();
//var_dump($params);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'providername' => [
                    'asc' => ['providername' => SORT_ASC],
                    'desc' => ['providername' => SORT_DESC],
                    'label' => 'Поставщик',
                    'default' => SORT_ASC
                ],
                'cityname' => [
                    'asc' => ['cityname' => SORT_ASC],
                    'desc' => ['cityname' => SORT_DESC],
                    'label' => 'Город',
                    'default' => SORT_ASC
                ],
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'providername' => $this->providername,
            'city_id' => $this->city_id,
            'days' => $this->days,
            'provider_id' => $this->provider_id,
        ]);

        $query->andFilterWhere(['like', 'providername', $this->providername])
            ->andFilterWhere(['like', 'cityname', $this->cityname]);
//            ->andFilterWhere(['like', 'days', $this->days]);

        return $dataProvider;
    }
}
