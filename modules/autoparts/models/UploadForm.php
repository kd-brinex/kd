<?php

namespace app\modules\autoparts\models;


use yii\base\Model;
use yii\web\UploadedFile;






class UploadForm extends Model
{


    /**
     * @var UploadedFile file attribute
     */
    public $file;
    public $flagpostav;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file'],
            [['flagpostav'], 'string'],

        ];
    }
    public function insertData($mas)
    {


        $query = PartOver::deleteAll(['flagpostav' =>$this->flagpostav]);




        foreach ($mas['f'] as $i=>$value) {
            $model1 = new PartOver();

            $temp = explode(';', $value);
            if ($i==0){
                $fields_name = $temp;


            }

            for ($j=0;$j<count($fields_name);$j++)
            {
                $text[trim($fields_name[$j])]=$temp[$j];
                $text['flagpostav'] = $this->flagpostav;


            }




                $model1->load(['PartOver' => $text]);
                $model1->save();
            }





        }

    public function attributeLabels()
    {
        return [
            'file' => 'Добавьте CSV файл',
            'flagpostav' => 'Флаг поставки',

        ];
    }







}
