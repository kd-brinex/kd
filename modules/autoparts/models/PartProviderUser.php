<?php

namespace app\modules\autoparts\models;

use Yii;

/**
 * This is the model class for table "part_provider_user".
 *
 * @property integer $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property integer $store_id
 * @property integer $provider_id
 * @property double $marga
 *
 * @property PartProvider $provider
 * @property TStore $store
 */
class PartProviderUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'part_provider_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'store_id', 'provider_id'], 'required'],
            [['store_id', 'provider_id'], 'integer'],
            [['marga'], 'number'],
            [['name'], 'string', 'max' => 200],
            [['login'], 'string', 'max' => 15],
            [['password'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'login' => 'Логин',
            'password' => 'Пароль',
            'store_id' => 'Магазин',
            'provider_id' => 'Поставщик запчастей',
            'marga' => 'Наценка в процентах',
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
    public function getStore()
    {
        return $this->hasOne(TStore::className(), ['id' => 'store_id']);
    }
    public function getStorelist()
    {
        $stores= new TStore();
        $astores=$stores->find()->asArray()->all();
        $res=[];
        foreach ($astores as $a){
            $res[$a['id']]=$a['name'];
        }
        return $res;
    }
    public function getCitySrok()
    {
//        var_dump($this->partProviderUsers);die;
        return PartProviderSrok::find()->andWhere(['city_id' => $this->store->city_id,'provider_id'=>$this->provider->id]);
    }

    public function getSrok()
    {
        return ($this->citySrok)?$this->citySrok->days:'99';
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
    /**
     * @return \yii\db\ActiveQuery
     */


}
