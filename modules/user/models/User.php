<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 18.03.15
 * Time: 15:46
 */
namespace app\modules\user\models;

use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{


    public function register()
    {
        return parent::register();
        // do your magic
    }

    public function rules()
    {
        return [
            // username rules
            ['username', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            ['username', 'match', 'pattern' => '/^[a-zA-Z]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'unique'],
            ['username', 'trim'],

            // telephone rules
            ['telephone', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            ['telephone', 'match', 'pattern' => '/^[0-9+]+$/'],
            ['telephone', 'string', 'min' => 0, 'max' => 20],
            ['telephone', 'unique'],
            ['telephone', 'trim'],

            // email rules
            ['email', 'required', 'on' => ['register', 'connect', 'create', 'update'],'message' => 'Необходимо заполнить поле «{attribute}».'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            ['email', 'trim'],

            // password rules
            ['password', 'required', 'on' => ['register']],
            ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],

//            ['user_id_1c', 'required', 'on' => ['update']],
            ['user_id_1c', 'string',  'max' => 30],
//            ['user_id_1c', 'match', 'pattern' => '/^[0-9+]+$/'],
            ['user_id_1c', 'unique'],
            ['user_id_1c', 'trim'],
        ];
    }

    public function scenarios()
    {
        return [
            'register' => ['username', 'email', 'telephone', 'password'],
            'connect' => ['username', 'email'],
            'create' => ['username', 'email', 'password'],
//            'update' => ['username', 'email', 'telephone', 'password'],
            'update' => ['username', 'email', 'telephone', 'password', 'user_id_1c'],
            'settings' => ['username', 'email', 'telephone', 'password']
        ];
    }

    public function attributeLabels()
    {
        return
            array_merge(parent::attributeLabels(),
                [
                    'telephone' => \Yii::t('user', 'Telephone'),
                    'user_id_1c' => \Yii::t('user', 'UserId1c'),
                ]);
    }
    public function findUserRemote()
    {
        $ur=new UserRemote();
        var_dump($ur->getRemoteUser($this->username,$this->password));die;
    }
    public function copyRemoteUser($params)
    {

    }
}