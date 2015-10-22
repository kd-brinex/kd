<?php

namespace app\modules\autoparts\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\autoparts\models\PartOver;

/**
 * PartOverSearch represents the model behind the search form about `app\modules\autoparts\models\PartOver`.
 */
class PartOverSearch extends PartOver
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id', 'quantity', 'srokmin', 'srokmax', 'lotquantity', 'skladid'], 'integer'],
            [['name', 'manufacture', 'pricedate', 'sklad', 'date_update'], 'safe'],
            [['price'], 'number'],
            [['code','flagpostav'], 'string'],
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
        $query = self::find();
        if(!empty($params[':code'])){
            $query ->andWhere('code = :code AND flagpostav = :flagpostav')
                   ->addParams([':code'=>$params[':code'], ':flagpostav' => $params[':flagpostav']]);
        }

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
            'code' => $this->code,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'srokmin' => $this->srokmin,
            'srokmax' => $this->srokmax,
            'lotquantity' => $this->lotquantity,
            'pricedate' => $this->pricedate,
            'skladid' => $this->skladid,
            'date_update' => $this->date_update,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'manufacture', $this->manufacture])
            ->andFilterWhere(['like', 'sklad', $this->sklad])
            ->andFilterWhere(['like', 'flagpostav', $this->flagpostav])
            ->andFilterWhere(['like', 'date_update', $this->date_update]);

        return $dataProvider;
    }
}
