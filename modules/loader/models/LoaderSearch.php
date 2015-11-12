<?php

namespace app\modules\loader\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\loader\models\Loader;

/**
 * LoaderSearch represents the model behind the search form about `app\modules\loader\models\Loader`.
 */
class LoaderSearch extends Loader
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blob_data'], 'safe'],
            [['id'], 'integer'],
            [['count','start','end'],'string']
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
        $query = Loader::find()
            ->select(
                ["count"=>"count(id)",
                    "start"=>"min(column_get(blob_data,'load_date' as datetime))",
                    "end"=>"max(column_get(blob_data,'load_date' as datetime))"])
        ;

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
        ]);

        $query->andFilterWhere(['like', 'blob_data', $this->blob_data]);

        return $dataProvider;
    }

}
