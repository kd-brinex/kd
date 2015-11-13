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
        return parent::beforeSave($insert);
    }

    public function setRoute($route) {
        $this->route = $route;
        $this->hash = md5($this->route);
    }

    public function setLinks($keywords,$route)
    {

        if(!empty($keywords))
        {
            $keywords = explode(",",$keywords);
            for($i = 0; $i< count($keywords); $i++)
            {
                //проверка чтобы ключ небыл фразой для замены
                if(!preg_match("/\{\{[a-zA-Z]*\}\}/",$keywords[$i]))
                {
                    $keywords[$i] = strtolower(trim($keywords[$i]));

                    if(($metaLinks = \app\modules\seotools\models\base\MetaLinks::findOne($keywords[$i])) != null)
                    {
                        if($metaLinks->seq_number > $i)
                        {
                            $metaLinks->seq_number = $i;
                            $metaLinks->link = $route;
                            $metaLinks->save();
                        }
                    }
                    else {

                        $metaLinks = new \app\modules\seotools\models\base\MetaLinks();
                        $metaLinks->keyword = $keywords[$i];
                        $metaLinks->seq_number = $i;
                        $metaLinks->link = $route;
                        $metaLinks->save();
                    }

                }
            }
        }
    }

}
