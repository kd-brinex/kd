<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user\controllers;

use dektrium\user\controllers\SecurityController as BaseController;

class SecurityController extends BaseController
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
}
