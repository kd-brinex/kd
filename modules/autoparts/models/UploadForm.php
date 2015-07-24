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


        PartOver::deleteAll(['flagpostav' =>$this->flagpostav]);
        $data=[];




        foreach ($mas['f'] as $i=>$value) {
            //$model1 = new PartOver();
            $value = iconv('cp1251','utf-8', $value);
            $temp = explode(';', trim($value));
            if ($i==0){
                $fields_name = $temp;
                $fields_name[] = 'flagpostav';
                $count_fields_name = count($fields_name);
                $code_index = array_search('code',$fields_name);

            }
            else {
                $temp[] = $this->flagpostav;
                if ($count_fields_name== count($temp)) {
                    $article = strtoupper($temp[$code_index]);
                    $temp[$code_index] = str_replace([' ', '-'], [], $article);
                    $data[] = $temp;


                }
//                for ($j = 0; $j < count($fields_name); $j++) {
//                    $data[][trim($fields_name[$j])] = $temp[$j];
//                    $data[]['flagpostav'] = $this->flagpostav;
//

//                }
            }



//                $model1->load(['PartOver' => $text]);
//                $model1->save();
            }
//        echo '<pre>';
//        print_r($data);
//        echo '<pre>';

        $sql = \Yii::$app->db->createCommand()->batchInsert('part_over', $fields_name,$data)->rawSql;
        $sql=str_replace('INSERT','INSERT IGNORE',$sql);
        \Yii::$app->db->createCommand($sql)->execute();


        }

    public function attributeLabels()
    {
        return [
            'file' => 'Добавьте CSV файл',
            'flagpostav' => 'Флаг поставки',

        ];
    }







}
