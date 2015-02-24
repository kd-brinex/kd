<?php

namespace app\modules\basket\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\modules\basket\models\Zakaz;

/**
 * ZakazSearch represents the model behind the search form about `app\modules\basket\models\Zakaz`.
 */
class ZakazSearch extends Zakaz
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pay_id', 'store_id'], 'integer'],
            [['session_id', 'user_id', 'user_name', 'user_telephon', 'user_email', 'adr_city', 'adr_adres', 'adr_index', 'zakaz', 'zakaz_date'], 'safe'],
            [['zakaz_summa'], 'number'],
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
        $query = Zakaz::find();

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
            'pay_id' => $this->pay_id,
            'store_id' => $this->store_id,
            'zakaz_summa' => $this->zakaz_summa,
            'zakaz_date' => $this->zakaz_date,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'user_telephon', $this->user_telephon])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'adr_city', $this->adr_city])
            ->andFilterWhere(['like', 'adr_adres', $this->adr_adres])
            ->andFilterWhere(['like', 'adr_index', $this->adr_index])
            ->andFilterWhere(['like', 'zakaz', $this->zakaz]);

        return $dataProvider;
    }
}
