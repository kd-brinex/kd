<?php

namespace app\modules\loader\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\loader\models\Loader;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * LoaderSearch represents the model behind the search form about `app\modules\loader\models\Loader`.
 */
class LoaderSearch extends Loader
{
    /**
     * @inheritdoc
     */
public $record_count;
public $start;
public $end;
    public function rules()
    {
        return [
            [['blob_data'], 'safe'],
            [['id'], 'integer'],
//            [['record_count','start','end',],'safe']

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

        $query = parent::find();

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
    public function searchInfo()
    {
        $record_count = new Expression('count(id)');
        $start = new Expression("left(column_get(blob_data,'load_date' as datetime),16)");
//        $end = new Expression("column_get(blob_data,'load_date' as datetime)");
        $query = parent::find()
            ->addSelect(
                ['record_count'=>$record_count,
                    'start'=>$start,
//                    'end'=>$end
                ])
            ->groupBy('start')
        ;
        $ret=$query->all();
//        var_dump($ret);die;
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination'=>false,
//        ]);
        $return['labels']=ArrayHelper::getColumn($ret,'start');
        $return['data']=ArrayHelper::getColumn($ret,'record_count');
//        var_dump($return);die;
        return $return;
    }

}
