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
        $ret=[
                [['name', 'public_email', 'location', 'telephone'], 'required', 'message' => 'Необходимо заполнить поле «{attribute}».'],
                [['public_email'], 'email'],
                [['name', 'location'], 'match', 'pattern' => '/^[а-яА-ЯёЁ-\s]+$/u', 'message' => 'Поле «{attribute}» может содержать только русские буквы.'],
                [['name'], 'string', 'max' => 15, 'tooLong' => 'Поле «{attribute}» не может превышать 15-ти символов'],
                [['telephone'], 'match', 'pattern' => '/^[0-9+]+$/', 'message' => 'Введите номер телефона в формате: +79876543210'],
                [['telephone'], 'unique'],
               ];
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ret=parent::attributeLabels();
        $ret=[
                'name' => 'Имя',
                'telephone' => 'Телефон',
                'public_email' => 'E-mail',
                'location' => 'Адрес'
             ];
        return $ret;
    }
    public function register()
    {
        return parent::register();
        // do your magic
    }

}