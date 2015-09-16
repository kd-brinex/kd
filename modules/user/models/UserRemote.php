<?php
namespace app\modules\user\models;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.09.15
 * Time: 17:11
 * Модуль для работы с пользователями на сайте kolesa-darom.ru
 */
class UserRemote extends Model
{
    private $user_home = "http://kolesa-darom.ru/netcat/m_admin/auth/";

    public function getRemoteUser($login,$password)
    {
        $params=['login'=>$login,'password'=>$this->encodePassword($password)];
        $curl=curl_init($this->$user_home);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
        $responce =  json_decode(curl_exec($curl),true);
        return $responce;
    }
    public function encodePassword($password)
    {
            return $password;
    }
}