<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "v_loader".
 *
 * @property integer $count
 * @property string $start
 * @property string $end
 * @property integer $time
 * @property string $timeonrec
 * @property string $recinsec
 */
class VLoader extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_loader';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count', 'time'], 'integer'],
            [['start', 'end'], 'safe'],
            [['timeonrec', 'recinsec'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'count' => 'Количество',
            'start' => 'Начало',
            'end' => 'Окончание',
            'time' => 'Время выгрузки',
            'timeonrec' => 'Время вставки',
            'recinsec' =>'Записей в секунду',
        ];
    }
}
