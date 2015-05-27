<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    public function actionDat($name)
    {
//        var_dump($name);die;
        $db = odbc_connect("DRIVER={DBISAM 4 ODBC Driver};ConnectionType=Local;CatalogName=smb://192.168.1.199/shared/PARTS/FNA_Data/;","admin","");
        $res = odbc_exec($db,"SELECT * FROM ".$name);
        echo odbc_num_rows($res)." rows found";
        while($row = odbc_fetch_array($res)) {
            print_r($row);
        }
    }
}
