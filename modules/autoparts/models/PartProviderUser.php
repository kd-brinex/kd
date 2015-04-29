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
            [['login', 'password', 'store_id', 'provider_id'], 'required'],
            [['store_id', 'provider_id'], 'integer'],
            [['marga'], 'number'],
            [['name'], 'string', 'max' => 200],
            [['login', 'password'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'login' => 'Login',
            'password' => 'Password',
            'store_id' => 'Store ID',
            'provider_id' => 'Provider ID',
            'marga' => 'Marga',
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
