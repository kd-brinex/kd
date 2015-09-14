<?php

namespace app\modules\images\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\images\models\ImgImage;

/**
 * ImgImageSearch represents the model behind the search form about `app\modules\file_upload\models\ImgImage`.
 */
class ImgImageSearch extends ImgImage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'table_id'], 'integer'],
            [['table', 'src', 'title', 'alt'], 'safe'],
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
        $query = ImgImage::find();

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
            'id' => $this->id,
            'table_id' => $this->table_id,
        ]);

        $query->andFilterWhere(['like', 'table', $this->table])
            ->andFilterWhere(['like', 'src', $this->src])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alt', $this->alt]);

        return $dataProvider;
    }
}
