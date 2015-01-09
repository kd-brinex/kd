<?php

namespace app\modules\site\tovar\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\site\tovar\models\Tovar;

/**
 * TovarSearch represents the model behind the search form about `app\modules\site\tovar\models\Tovar`.
 */
class TovarSearch extends Tovar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tip_id', 'category_id', 'name'], 'safe'],
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
        $query = Tovar::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($params,$this->validate(),$this->load($params));die;
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'tip_id', $this->tip_id])
            ->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function category_list($params)
    {
        $query = Tovar::find()
//            select * from tovar  where id_store=999 and price > 0 and tip_id='shina' and value_char='shina' group by id
            ->where(['tip_id'=>$params['tip_id']
                    ,'value_char'=>$params['tip_id']
                    ,'id_store'=>999])
            ->groupBy (['id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        var_dump($params,$this->validate(),$this->load($params));die;
//       if (!($this->load($params) && $this->validate())) {
//            return $dataProvider;
//        }
//        var_dump($params);die;

//        $query->andFilterWhere(['like', 'tip_id', $params['tip_id']])->all();

        return $dataProvider;
    }

}
