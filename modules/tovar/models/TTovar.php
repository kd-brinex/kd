<?php

namespace app\modules\tovar\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "t_tovar".
 *
 * @property string $id
 * @property string $tip_id
 * @property string $category_id
 * @property string $name
 * @property string $description
 *
 * @property Basket[] $baskets
 * @property TValue[] $tValues
 */
class TTovar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_tovar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tip_id', 'category_id', 'name'], 'required'],
            [['id', 'category_id'], 'string', 'max' => 9],
            [['tip_id'], 'string', 'max' => 25],
            [['name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tip_id' => 'Tip ID',
            'category_id' => 'Category ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::className(), ['tovar_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTValues()
    {
        return $this->hasMany(TValue::className(), ['tovar_id' => 'id']);
    }

    public function search_params($params)
    {
        if (empty($params['tip_id'])){return [];}
        $query = new Query();
        $query->select('p.id, p.name, p.title')
        ->from('t_tovar t')
            ->leftJoin('t_value v','v.tovar_id=t.id')
            ->leftJoin('t_param p','v.param_id=p.id')
        ->where('t.tip_id=:tip_id',[':tip_id'=>$params['tip_id']])
            ->orderBy('p.name')
            ->distinct();
        return $query->all();
    }

    public function search_tip_id($params)
    {
        $query = new Query();
        $query->from('t_tovar t');
        $options=[];
        $joptions = isset($params['options']) ? $params['options'] : [];
//        var_dump($joptions);die;
        if (is_array($joptions)){
            foreach ($joptions as $key=> $value)
            {
                if (!empty($key)){$options[$key]=$value;}
            }
        }
        else
        {$options=json_decode($joptions);}
        $n = 1;
//        var_dump($joptions);die;
        foreach ($options as $id => $value) {
            if (!empty($value)){
            $name = 't' . $n;
            $query->leftJoin('t_value ' . $name, 't.id=' . $name . '.tovar_id and ' . $name . '.param_id=:' . $name, [':' . $name => $id])
                ->andWhere([$name . '.value_char' => $value])
                ->addSelect($name . '.value_char ' . $id);
            $n = $n + 1;}
        }
        $query->leftJoin('t_price p', 'p.id_tovar=t.id and p.id_store=:store_id', [':store_id' => $params['store_id']])
            ->addSelect('p.price,p.id_store,p.count,t.id,t.name,t.tip_id,t.category_id');
        $query->limit($params['page_size'])->offset(($params['page'] - 1) * $params['page_size']);
        $query->andWhere($params['where']);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
//var_dump($dataProvider);die;
        return $dataProvider;

    }
    public function search_all()
    {
        $query=$this->find();
        $query->groupBy(['tip_id'])->select('id, tip_id');
        return $query->all();
    }
}
