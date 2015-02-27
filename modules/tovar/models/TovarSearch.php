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
//            select * from tovar  where id_store=999 and price > 0 and tip_id='shina' and value_char='shina' group by id
            ->andWhere(['tip_id'=>$params['tip_id']])
            ->groupBy (['id']);

        $dataProvider = new ActiveDataProvider(['query' => $query,]);
        return $dataProvider;
    }
    public function find_tovar_param($params)
    {
//        $city_id=Yii::$app->request->cookies['city'];
        $p['id']=$params['id'];
//        $p['id_store']=999;
//        $query = $this->find()->andwhere('(id=:id) and (id_store=:id_store) and not(title is null) and (value_char != \'\')',$p);
        $query = Tovar::find()->andwhere('(id=:id)',$p);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
//        $dataProvider->pagination=false;
        return $dataProvider;
    }

    public static function category_menu()
    {
        $rows = (new \yii\db\Query())
            ->select('name as label')
            ->from('tovar_category')
            ->all();
        for($i=0;$i<count($rows);++$i){
            $rows[$i]['url']='/tovars/'.$rows[$i]['label'];
        }
        return $rows;
    }

}
