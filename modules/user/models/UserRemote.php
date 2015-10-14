<?php
namespace app\modules\user\models;

use yii\base\Model;
use yii\db\Query;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 15.09.15
 * Time: 17:11
 * Модуль для работы с пользователями на сайте kolesa-darom.ru
 */
class UserRemote extends Model
{
    private $user_home = "http://kolesa-darom.ru/netcat/m_admin/auth/index.php";

    public function getRemoteUser($login,$password)
    {
        $password=$this->encodePassword($password);
        $login=$this->encodeLogin($login);
        $url=$this->user_home.'?login='.$login.'&password='.$password;
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($curl);
        curl_close($curl);
//        return $responce;
        return unserialize(trim(base64_decode($responce)));
//        return unserialize('a:1:{i:0;a:116:{s:7:"User_ID";s:3:"526";i:0;s:3:"526";s:8:"Password";s:41:"*FD571203974BA9AFE270FE62151AE967ECA5E0AA";i:1;s:41:"*FD571203974BA9AFE270FE62151AE967ECA5E0AA";s:7:"Checked";s:1:"1";i:2;s:1:"1";s:18:"PermissionGroup_ID";s:1:"8";i:3;s:1:"8";s:8:"Language";s:7:"Russian";i:4;s:7:"Russian";s:7:"Created";s:19:"2015-01-14 16:51:24";i:5;s:19:"2015-01-14 16:51:24";s:11:"LastUpdated";s:19:"2015-09-16 12:33:42";i:6;s:19:"2015-09-16 12:33:42";s:5:"Email";s:13:"hmf@yandex.ru";i:7;s:13:"hmf@yandex.ru";s:9:"Confirmed";s:1:"0";i:8;s:1:"0";s:16:"RegistrationCode";s:0:"";i:9;s:0:"";s:7:"Keyword";N;i:10;N;s:5:"Login";s:11:"79375815223";i:11;s:11:"79375815223";s:4:"Name";s:5:"Марат";i:12;s:5:"Марат";s:10:"FamilyName";s:8:"Хусаинов";i:13;s:8:"Хусаинов";s:9:"ForumName";N;i:14;N;s:11:"ForumAvatar";N;i:15;N;s:14:"ForumSignature";N;i:16;N;s:8:"FullName";s:0:"";i:17;s:0:"";s:5:"Phone";N;i:18;N;s:4:"City";s:16:"Набережные Челны";i:19;s:16:"Набережные Челны";s:7:"Address";N;i:20;N;s:10:"Additional";N;i:21;N;s:17:"InsideAdminAccess";s:1:"0";i:22;s:1:"0";s:12:"Catalogue_ID";s:1:"0";i:23;s:1:"0";s:9:"Auth_Hash";N;i:24;N;s:8:"UserType";s:0:"";i:25;s:0:"";s:6:"CityID";s:3:"109";i:26;s:3:"109";s:7:"account";N;i:27;N;s:13:"ncAttemptAuth";s:1:"0";i:28;s:1:"0";s:9:"ncAccount";s:1:"0";i:29;s:1:"0";s:8:"UrAdress";N;i:30;N;s:4:"Bank";N;i:31;N;s:8:"Rasschet";N;i:32;N;s:8:"Korschet";N;i:33;N;s:3:"BIK";N;i:34;N;s:3:"INN";N;i:35;N;s:3:"KPP";N;i:36;N;s:4:"OGRN";N;i:37;N;s:11:"Gendirektor";N;i:38;N;s:7:"Glavbux";N;i:39;N;s:8:"GrAdress";N;i:40;N;s:7:"MarkaID";N;i:41;N;s:7:"ModelID";N;i:42;N;s:12:"NacenkaDiski";N;i:43;N;s:12:"NacenkaShiny";N;i:44;N;s:15:"NacenkaAksesyar";N;i:45;N;s:10:"AutoPodbor";s:29:",34950,6506,37319,32314,17646";i:46;s:29:",34950,6506,37319,32314,17646";s:11:"bonuspoints";s:1:"0";i:47;s:1:"0";s:14:"bonusnachislen";s:1:"0";i:48;s:1:"0";s:9:"Otchestvo";s:0:"";i:49;s:0:"";s:9:"Psevdonim";s:0:"";i:50;s:0:"";s:14:"datarozhdeniya";s:10:"27.01.2015";i:51;s:10:"27.01.2015";s:6:"avatar";N;i:52;N;s:9:"checkball";s:1:"1";i:53;s:1:"1";s:10:"CheckEmail";s:1:"1";i:54;s:1:"1";s:15:"CheckRegister1c";N;i:55;N;s:11:"AutoKatalog";s:1468:";{"catalog_code":"156550","catalog":"EU","model_code":"NDE120L-DEMDYW","compl_code":"028","model_name":"COROLLA SED\/WG (UKP)-CDE120,NDE120,ZZE12#","sysopt":"","vdate":"","vin":"","frame":"NDE120","number":"","user_id":"526"};{"catalog_code":"221230","catalog":"EU","model_code":"TA62L-ACMECC","compl_code":"031","model_name":"CARINA-TA6#,CA60","sysopt":"","vdate":"","vin":"","frame":"TA62","number":"","user_id":"526"};{"catalog_code":"161230","catalog":"EU","model_code":"KE35L-KSB","compl_code":"046","model_name":"COROLLA\/TRUENO-KE3#,5#,TE3#,47,51","sysopt":"","vdate":"","vin":"","frame":"KE35","number":"","user_id":"526"};{"catalog_code":"332280","catalog":"EU","model_code":"JZS147R-BEPQFW","compl_code":"003","model_name":"LEXUS GS300-JZS147","sysopt":"","vdate":"","vin":"","frame":"JZS147","number":"","user_id":"526"};{"catalog_code":"261230","catalog":"EU","model_code":"RT104-HDF","compl_code":"026","model_name":"CORONA-RT10#,11#","sysopt":"","vdate":"","vin":"","frame":"RT104","number":"","user_id":"526"};{"catalog_code":"162510","catalog":"EU","model_code":"NRE150L-AEFNKW","compl_code":"028","model_name":"COROLLA SED    (JPP)-ADE150,NDE150,NRE150,ZRE151,ZZE150","sysopt":"442W","vdate":"","vin":"","frame":"NRE150","number":"","user_id":"526"};{"catalog_code":"284580","catalog":"EU","model_code":"ACV40L-AEMNKW","compl_code":"002","model_name":"CAMRY-ACV40,GSV40","sysopt":"450W","vdate":"","vin":"","frame":"ACV40","number":"","user_id":"526"}";i:56;s:1468:";{"catalog_code":"156550","catalog":"EU","model_code":"NDE120L-DEMDYW","compl_code":"028","model_name":"COROLLA SED\/WG (UKP)-CDE120,NDE120,ZZE12#","sysopt":"","vdate":"","vin":"","frame":"NDE120","number":"","user_id":"526"};{"catalog_code":"221230","catalog":"EU","model_code":"TA62L-ACMECC","compl_code":"031","model_name":"CARINA-TA6#,CA60","sysopt":"","vdate":"","vin":"","frame":"TA62","number":"","user_id":"526"};{"catalog_code":"161230","catalog":"EU","model_code":"KE35L-KSB","compl_code":"046","model_name":"COROLLA\/TRUENO-KE3#,5#,TE3#,47,51","sysopt":"","vdate":"","vin":"","frame":"KE35","number":"","user_id":"526"};{"catalog_code":"332280","catalog":"EU","model_code":"JZS147R-BEPQFW","compl_code":"003","model_name":"LEXUS GS300-JZS147","sysopt":"","vdate":"","vin":"","frame":"JZS147","number":"","user_id":"526"};{"catalog_code":"261230","catalog":"EU","model_code":"RT104-HDF","compl_code":"026","model_name":"CORONA-RT10#,11#","sysopt":"","vdate":"","vin":"","frame":"RT104","number":"","user_id":"526"};{"catalog_code":"162510","catalog":"EU","model_code":"NRE150L-AEFNKW","compl_code":"028","model_name":"COROLLA SED    (JPP)-ADE150,NDE150,NRE150,ZRE151,ZZE150","sysopt":"442W","vdate":"","vin":"","frame":"NRE150","number":"","user_id":"526"};{"catalog_code":"284580","catalog":"EU","model_code":"ACV40L-AEMNKW","compl_code":"002","model_name":"CAMRY-ACV40,GSV40","sysopt":"450W","vdate":"","vin":"","frame":"ACV40","number":"","user_id":"526"}";s:8:"bonusall";N;i:57;N;}}');
    }
    public function encodePassword($password)
    {
        $query_pass=new Query();
        $exppass = new Expression('password(:password)',[':password'=>$password]);
        $query_pass->select(['password'=>$exppass]);
        $hash=$query_pass->scalar();
//        var_dump($hash);die;
            return $hash;
    }
    public function encodeLogin($login)
    {
        return md5($login);
    }
    public function createUser()
    {
        $user = new User();

    }
    public function sinh_fields()
    {
        return[
        'username'=>'Login',
            'email'=>'Email',

        ];
    }
}