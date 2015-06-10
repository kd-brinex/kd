<?php

namespace app\modules\autoparts\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\autoparts\models\PartProviderUser;

/**
 * PartProviderUserSearch represents the model behind the search form about `app\modules\autoparts\models\PartProviderUser`.
 */
class PartProviderUserSearch extends PartProviderUser
{
    /**
     * @inheritdoc
     */
    public $srok;
    public function rules()
    {
        return [
            [['id', 'store_id', 'provider_id'], 'integer'],
            [['name', 'login', 'password'], 'safe'],
            [['marga'], 'number'],
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
        $query = PartProviderUser::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere([
//            'id' => $this->id,
            'store_id' => $this->store_id,
            'provider_id' => $this->provider_id,
//            'marga' => $this->marga,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
    public function getUserProvider($params)
    {
//        $dataProvider = $this->find()
//            ->andWhere(['store_id'=>$params['store_id']])
//            ->andWhere(['provider_id'=>$params['provider_id']])->all();
        $query = PartProviderUser::find()->andWhere($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
