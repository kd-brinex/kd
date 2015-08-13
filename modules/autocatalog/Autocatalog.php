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
        $properties =get_object_vars($this->catalog);
        foreach ($properties as $name => $var)
        {
        $imageExpr=new Expression("'".((is_array($var))?implode('|',$var):$var)."'");
        $query->addSelect([ucfirst($name)=>$imageExpr]);
        }
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
    public function getResult($method,$prm)
    {
        if(!method_exists($this->catalog,$method)){return result;}
        $dataProvider=$this->getDataProvider($this->catalog->$method($prm));
        $result['dataProvider'] = $dataProvider;
        $result['properties'] =get_object_vars($this->catalog);

        return $result;

    }
    public function getModelList($prm)
    {
        $data=$this->getResult(__FUNCTION__,$prm);
        $data['dataProvider']->pagination=false;
        $model = $data['dataProvider']->models;
        $data['models'] = [];
        foreach ($model as $item) {
            $data['models'][$item['model_name']][] = $item;
        }
        return $data;
    }

    /**
     * get CatalogList - список модификаций выбраной модели
     * @param $prm
     * @return array
     */
    public function getCatalogList($prm)
    {
        $data=$this->getResult(__FUNCTION__,$prm);
        $data['dataProvider']->pagination=false;
        return $data;
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