<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 20.04.15
 * Time: 10:33
 */

namespace auto\autoparts\providers;
use yii\db\ActiveRecord;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\gii\Module;

class Provider extends ActiveRecord{
    public static function tableName()
    {
        return 'part_provider';
    }
}