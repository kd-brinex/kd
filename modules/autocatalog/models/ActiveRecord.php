<?php
namespace app\modules\autocatalog\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 02.10.15
 * Time: 15:11
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->getModule('autocatalog')->db;
    }

}