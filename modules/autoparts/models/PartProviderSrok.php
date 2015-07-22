<?php

namespace app\modules\autoparts\models;

use Yii;
use app\modules\city\models\City;

/**
 * This is the model class for table "part_provider_srok".
 *
 * @property integer $provider_id
 * @property integer $city_id
 * @property integer $days
 *
 * @property PartProvider $provider
 * @property GeobaseCity $city
 */
class PartProviderSrok extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_provider_srok';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
              [['provider_id', 'city_id', 'days'], 'integer'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider_id' => 'Код поставщика',
            'providerName'=>'Поставщик',
            'city_id' => 'Код города',
            'cityName' => 'Город',
            'days' => 'Количество дней',
            'name' => 'Город'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(PartProvider::className(), ['id' => 'provider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function getProvidername()
    {
        return $this->provider->name;
    }
    public function getCityname()
    {
        return $this->city->name;
    }
    public function getCitylist()
    {
        $citys= new City();
        $acitys=$citys->find()->leftJoin('t_store','t_store.city_id=geobase_city.id')->andWhere('not t_store.addr is null')->asArray()->all();
        $res=[];
        foreach ($acitys as $a){
            $res[$a['id']]=$a['name'];
        }
        return $res;
    }
    public function getProviderDD(){
        $d=new PartProvider();
        $dd= $d->find()->asArray()->All();
//        var_dump($dd);die;
        $res=[];
        foreach ($dd as $a){
            $res[$a['id']]=$a['name'];
        }
        return $res;
    }
}
