<?php
namespace app\modules\catalog\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 23.06.15
 * Time: 12:21
 */
use Yii\db\Connection;

class Translate
{
    public static $db;

    public static function translate_yandex($text)
    {

        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://translate.yandex.net/api/v1.5/tr.json/translate/");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://translate.yandex.net/api/v1.5/tr.json/translate'
                . '?key=trnsl.1.1.20150623T075430Z.6e189d983c322a35.f867a35d57a636a28a71f4ff14e0c6ce7c74576d'
                . '&text=' . urlencode($text)
                . '&lang=RU',
        ]);

        $json = curl_exec($ch);;
        $ret = json_decode($json,true);
//        var_dump($ret);die;
        $ret_text = ($ret['code'] == 200) ? $ret['text'][0] : '';
        $ret_text = ($ret_text===$text)?'':$ret_text;
        return $ret_text;
    }

    public static function translate_google($text)
    {
        return '';
    }

    public function translation($table_name, $field_en, $field_ru)
    {
        $connect_param = ['dsn'=>self::$db['dsn'],
            'username' => self::$db['username'],
            'password' => self::$db['password'],
            'charset' => self::$db['charset']];
        $query= new Connection($connect_param);
        $query->open();

        $command=$query->createCommand('select '.$field_en.' from '.$table_name.' where '.$field_ru . ' is null');
        $a = $command->queryAll();
//        var_dump($a);die;
        foreach ($a as $r) {
//            var_dump($r);die;
            $t = $this->translate_yandex($r[$field_en]);
//            var_dump($t,111);die;

            if ($t != '') {
//                $sql='UPDATE '.$table_name.' SET '.$field_ru.'='.$t.' WHERE '.$field_en.'='.$r[$field_en];
                $r=$command->update($table_name,[$field_ru=>$t],$field_en."='".$r[$field_en]."'")->execute();
//var_dump($r);die;
//                $this->createCommand($sql);
//                ->update($table_name, [$field_en => $t], [$field_en => $r->$field_en])->execute();

            }
        }
    }
}