<?php

namespace app\modules\seotools\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\seotools\models\base\Infotext;

/**
 * InfotextSearch represents the model behind the search form about `app\modules\seotools\models\base\Infotext`.
 */
class InfotextSearch extends Infotext
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meta_id', 'city_id'], 'integer'],
            [['infotext_before', 'infotext_after'], 'safe'],
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
        $query = Infotext::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'meta_id' => $this->meta_id,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'infotext_before', $this->infotext_before])
            ->andFilterWhere(['like', 'infotext_after', $this->infotext_after]);

        $query->orderBy('city_id');

        return $dataProvider;
    }

}
