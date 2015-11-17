<?php

namespace app\modules\tovar\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tovar\models;

/**
 * TovarSearch represents the model behind the search form about `app\modules\tovar\models\Tovar`.
 */
class TovarSearch extends Tovar


{
    /**
     * @inheritdoc
     */
    public $image;
    public $srok;
    public $hash;
    public $inbasket;

    public function rules()
    {
        return [
            [['id', 'tip_id', 'category_id', 'name', 'image'], 'safe'],
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

//$query=$this->find_tovar_param($params);
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
            ->andWhere(['tip_id' => $params['tip_id']])
            ->groupBy(['id']);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $dataProvider;
    }

    public function find_tovar_param($params)
    {
        $p['id'] = $params['id'];
        $query = TovarParam::find()->andwhere('(id=:id) and (title<>\'\') and not (value_char is null)', $p);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }
    public function find_tovar($params)
    {
        $p['id'] = $params['id'];
        $query = Tovar::find()->andwhere('id=:id', $p);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }
    public static function category_menu()
    {
        $rows = (new \yii\db\Query())
            ->select('name as label')
            ->from('tovar_category')
            ->all();
        for ($i = 0; $i < count($rows); ++$i) {
            $rows[$i]['url'] = '/tovars/' . $rows[$i]['label'];
        }
        return $rows;
    }

    public function search_details($params)
    {
        $p['store_id'] = (isset($params['store_id']) ? $params['store_id'] : 109);
        $p['detailnumber'] = (isset($params['article']) ? $params['article'] : '');
        $query = new Yii\db\Query();

        return $query->from('finddetails')->where($p)->all();

    }


}
