<?php

/**
* @author: Eugene Brx
* @email: compuniti@mail.ru
* @date: 14.09.15
* @time: 13:52
*/
namespace app\modules\autoparts\components;

use \yii\base\Component;

class BrxModel extends Component{

    public function run($modelName, $method, array $options = null){
//        $model = $this->getModel($modelName);
        var_dump($this->getModel($modelName));die;
    }

    private function getModel($modelName){
        $model = '\app\modules\autoparts\models\\'.$modelName;
        return ((new $model) instanceof \yii\db\ActiveRecord) ?: $model;
    }

    public function search(){

    }
}

?>