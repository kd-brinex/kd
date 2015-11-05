<?php
namespace app\modules\autocatalog\models;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 16:02
 */
class FrameSearch extends ActiveRecord
{
//    public function rules()
//    {
//        return [
//            [['cat_folder','name','cat_code','img'], 'safe'],
//        ];
//    }

//    public function attributeLabels()
//    {
//        return [
////            '' => 'Регион',
//        ];
//    }

    public static function tableName()
    {
        return 'v_frame';
    }

    public function search($params=[])
    {
        $frame=substr($params['vin'],0,5);
        $serial=substr($params['vin'],6,7);
        $query =parent::find();
        $query

            ->where('frame_code=:frame_code and serial_number=:serial_number',[':frame_code'=>$frame,':serial_number'=>$serial])
            ->limit(1);
        return $query;
    }
}