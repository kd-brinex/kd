<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class PartsSearch extends ActiveRecord
{
    public function rules()
    {
        return [
//            [['cat_folder','name','cat_code','img'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
//            '' => 'Регион',
        ];
    }

    public static function tableName()
    {
        return 'v_parts';
    }

    public function search($params=[])
    {
//        var_dump($params);die;
        $query =parent::find()
            ->distinct()
            ->where('cat_folder=:cat_folder',[':cat_folder'=>$params['cat_folder']])
            ->andWhere('sect=:sect',[':sect'=>$params['sect']])
            ->andWhere('sub_sect=:sub_sect',[':sub_sect'=>$params['sub_sect']])
    ;


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>false,
        ]);
        $model = $dataProvider->models;
        $arr = [];
//        $arr['params'] = $page->url_params;
//var_dump($model);die;
        foreach ($model as $item) {
            $arr['models'][$item['number']][] = $item;
            $arr['labels'][$item['number']][$item['x1'] . 'x' . $item['y1']] = $item;
        }
        return $arr;
//        $this->load($params);

        return $models;
    }
}