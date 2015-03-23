<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 21.03.15
 * Time: 12:49
 */
namespace app\modules\user\models;

use dektrium\user\models\Profile as BaseUser;
class Profile extends BaseUser
{
    public function rules()
    {
        $ret=parent::rules();
        $ret[]=[['telephone'], 'string', 'max' => 15];
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ret=parent::attributeLabels();
        $ret['telephone']='Телефон';
        return $ret;
    }
    public function register()
    {
        return parent::register();
        // do your magic
    }

}