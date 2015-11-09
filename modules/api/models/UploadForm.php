<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 09.11.15
 * Time: 8:53
 */
namespace app\modules\api\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $textFile;

    public function rules()
    {
        return [
            [['textFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, sql'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->textFile->saveAs('uploads/' . $this->textFile->baseName . '.' . $this->textFile->extension);
            return true;
        } else {
            return false;
        }
    }
    public function getText()
    {
        $file=FileHelper::localize($this->textFile);
//        FileHelper::getMimeType()
        $f=file('uploads/'.$file->name);
        return $f;
    }
    public function getSql()
    {
        $f=$this->getText();
//        var_dump($f,'<br>');
        $f=$this->findDuble($f);
//        var_dump($f);die;
        $text='';
        foreach($f as $s) {
            $text.=$s;
//            var_dump($r[0]);die;

        }
        $r = \Yii::$app
        ->db
        ->createCommand('CALL `text2insert`(:intext);', [':intext' => $text])
        ->queryAll();
//        var_dump($r);die;
         return $r[0]['query'];
    }
    public function findDuble($str){

//        $str = str_replace("*},", "*},<br>", $str);
//        var_dump($str);die;
        foreach($str as &$st)
        {
            $d = explode('*}',$st);
//        var_dump($d);die;
        foreach($d as &$p)
        {
            $s=$p;
            $s=str_replace("{*", "", $s);
//            $s=str_replace(chr(65279), "", $s);
            $s=str_replace(",", " ", $s);
//            $s=str_replace("'", " ", $s);
//            $s=str_replace("  ", " ", $s);
            $words=explode(' ',$s);
//            var_dump($words);
$du=[];
            foreach($words as $word)
            {
                $du[$word]=(isset( $du[$word]))?2:1;
            }
//            var_dump($du);die;
            foreach($du as $key=>$v)
            {
                if ($v==2){
                   $p= str_replace($key,'<span style="color:red">'.$key.'</span>',$p);
                }
            }
        }

$st=implode('*}<br>',$d);

        }
        return $str;
    }
}