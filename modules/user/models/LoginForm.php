<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\models;

use dektrium\user\models\LoginForm as BaseModel;
use app\modules\user\Finder;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends BaseModel
{

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = $this->finder->findUserByUsernameOrEmailOrTelephone($this->login);
            return true;
        } else {
            return false;
        }
    }
    public function __construct(Finder $finder, $config = [])
    {
        $finder->userQuery    = \Yii::$container->get('UserQuery');
                $finder->profileQuery = \Yii::$container->get('ProfileQuery');
                $finder->tokenQuery   = \Yii::$container->get('TokenQuery');
                $finder->accountQuery = \Yii::$container->get('AccountQuery');
        parent::__construct($finder ,$config);
    }
}
