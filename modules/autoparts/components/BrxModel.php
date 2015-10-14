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
        $model = $this->getModel($modelName);
        $modelData = $this->dataForModel($options);
        return $model->$method($modelData)->getModels();
    }

    private function getModel($modelName){
        $model = '\app\modules\autoparts\models\\'.$modelName;
        return ((new $model) instanceof \yii\db\ActiveRecord) ? new $model : false;
    }

    private function dataForModel($data){
        if(is_array($data)){
            foreach($data as $key => $value){
                if($key != 'login' && $key != 'password')
                    $data[':'.$key] = $value;

                unset($data[$key]);
            }
            return $data;
        } else return false;
    }
}

?>