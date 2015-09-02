<?php
namespace app\components;

use Yii;



class BrxSession extends \yii\web\Session{

    public $oldSessId;



    public function init(){

        parent::init();
    }

    public function open(){

        if($this->getIsActive()) {
            $cookies = Yii::$app->request->cookies;
            if(isset($cookies['OLDSESSID'])){
                $this->oldSessId = $cookies->getValue('OLDSESSID');
            } else {
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'OLDSESSID',
                    'value' => $this->id
                ]));
            }
        }

        parent::open();
    }

    public function destroy(){
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'OLDSESSID',
                'value' => ''
            ]));

        parent::destroy();
    }
}