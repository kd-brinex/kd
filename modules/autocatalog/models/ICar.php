<?php
namespace app\modules\autocatalog\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 05.08.15
 * Time: 16:49
 */

interface ICar
{
    public function getModelList($prm); //Массив моделей [регион] [модель] [модификация]
    public function getRegionList();    //Список регионов
    public function getVehicle($prm);

}