<?php

namespace app\modules\seotools\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use app\modules\seotools\models\base\MetaBase;

/**
 * @inheritdoc
 */
class Meta extends MetaBase
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->created_at = new Expression('NOW()');
        $this->updated_at = new Expression('NOW()');
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        $this->hash = md5($this->route);
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->hash = md5($this->route);
        $this->first_keyword = mb_strtolower($this->getFirstKeyword());
        return parent::beforeSave($insert);
    }

    public function setRoute($route) {
        $this->route = $route;
        $this->hash = md5($this->route);
    }

    public function getFirstKeyword()
    {
        if(!empty($this->keywords))
        {
            $key_array = explode(",",$this->keywords);
            $first_keyword = $key_array[0];
        }
        else $first_keyword = null;
        return $first_keyword;
    }
}
