<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 13.07.15
 * Time: 15:34
 */

namespace app\modules\parser\controllers;

use yii\web\Controller;
use app\modules\parser\models\Parser;

class ParserController extends Controller
{
    public function actionIndex()
    {
        $params=\Yii::$app->request->queryParams;
        $parser = new Parser($params);
        if (!empty($params['url'])){
        $parser->parse($params['url']);}
//var_dump($parser->result);die;
        return $this->render('index',['result'=>$parser->result,'params'=>$params]);
    }

}