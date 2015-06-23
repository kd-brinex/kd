<?php
namespace app\modules\catalog\models;
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 18.06.15
 * Time: 16:36
 */
class AvQuery extends \yii\db\Query

{
    public static $db;

    public function createCommand($db = null)
    {
        if ($db === null) {
            $db = \Yii::createObject(self::$db);
        }
        list ($sql, $params) = $db->getQueryBuilder()->build($this);

        return $db->createCommand($sql, $params);
    }


}