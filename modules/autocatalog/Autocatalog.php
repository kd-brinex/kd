<?php
namespace app\modules\autocatalog;
use \yii\base\Module;
use \yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.08.15
 * Time: 9:54
 */
class Autocatalog extends Module
{

    public $db;         //Настройка доступа к БД
    public $model;      //Описание модели доступа к данным
    public $image_path;      //Путь к рисункам
    public $marka;      //Марка авто
    public $connect;    //Connection к базе
    public $catalog;    //Компонет доступа к базе модели

    public function init()
    {
        parent::init();
        $this->connect= \Yii::createObject($this->db);
        $this->catalog = \Yii::createObject($this->model);
//        $this->connect=new Connection($this->db);

    }
    private function getDataProvider($query){
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $imageExpr=new Expression('CONCAT_WS("/","'.$this->image_path.'","Titles")');
        $query->addSelect(['image_path'=>$imageExpr]);
        $dataProvider->db=$this->connect;
        return $dataProvider;
    }
    /**
     * getMarkList - список автомобильных марок
     * @return array
     */
    public function getMarkList()
    {
        return [];
    }

    /**
     * getModellist - список моделей выбраной марки
     * @param $prm
     */
    public function getModelList($prm)
    {
        $dataProvider=$this->getDataProvider($this->catalog->getModelList($prm));
        $dataProvider->pagination=false;
        $model = $dataProvider->models;
        $arr = [];
        foreach ($model as $item) {
            $arr[$item['model_name']][] = $item;
        }
        return $arr;
    }

    /**
     * get CatalogList - список модификаций выбраной модели
     * @param $prm
     * @return array
     */
    public function getCatalogList($prm=[])
    {
        $funcname=__FUNCTION__;
        if(!method_exists($this->catalog,$funcname)){return null;}
        $dataProvider=$this->getDataProvider($this->catalog->$funcname($prm));
        $dataProvider->pagination=false;
        return $dataProvider;
    }
    public function getTranslate($lang_code,$lex_desc)
    {
        $funcname=__FUNCTION__;
        if($lang_code=='EN' or !method_exists($this->catalog,$funcname) ){return $lex_desc;}
        $query=$this->catalog->$funcname($lang_code,$lex_desc);
        $desc=$query->all();
        var_dump($desc);
    }

    /**
     * getCharacterList - список характеристик выбраной модификации
     * @param $prm
     * @return array
     */
    public function getCharacterList($prm)
    {
        $res=[];

        return $res;
    }

    /**
     * getNodeList - список узлов в выбранной модификации
     * @param $prm
     * @return array
     */
    public function getNodeList($prm)
    {
        $res=[];

        return $res;
    }

    /**
     * getPageList - список разделов в выбранном узле
     * @param $prm
     * @return array
     */
    public function getPageList($prm)
    {
        $res=[];

        return $res;
    }

    /**
     * getPartsList - список запчастей для раздела узла.
     * @param $prm
     * @return array
     */
    public function getPartsList($prm)
    {
        $res=[];

        return $res;
    }
}