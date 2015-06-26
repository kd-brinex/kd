<?php

namespace app\modules\autoparts\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * PartProviderSearch represents the model behind the search form about `app\modules\autoparts\models\PartProvider`.
 */
class PartProviderSearch extends PartProvider
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'weight'], 'integer'],
            [['name', 'flagpostav'], 'safe'],
            [['enable'],'integer']
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
        $query = PartProvider::find();

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
            'enable' => $this->enable,
            'weigth' => $this->weight,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'flagpostav', $this->flagpostav])
            ->andFilterWhere(['like', 'wheight', $this->weight]);

        return $dataProvider;
    }
}
