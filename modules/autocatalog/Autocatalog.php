<?php
namespace app\modules\catalog;
use \yii\base\Module;

/**
 * Created by PhpStorm.
 * User: marat
 * Date: 03.08.15
 * Time: 9:54
 */
class Autocatalog extends Module
{
    public $db;         //Параметры подключения к БД
    public $models;     //Модель доступа кБД
    public $image;      //Путь к рисункам
    public $marka;      //Марка авто
    public $model_code; //Код модели
    public $model_name; //Название модели
    public $catalog;    //Код каталога
    public $page;       //Код страницы каталога

    public function init()
    {
        parent::init();

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
        $res=[];

        return $res;
    }

    /**
     * get CatalogList - список модификаций выбраной модели
     * @param $prm
     * @return array
     */
    public function getCatalogList($prm)
    {
        $res=[];

        return $res;
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