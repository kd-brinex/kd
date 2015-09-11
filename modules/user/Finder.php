<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\modules\user;

use dektrium\user\Finder as BaseObject;
use yii\db\ActiveQuery;

/**
 * Finder provides some useful methods for finding active record models.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Finder extends BaseObject
{

    public function findUserByTelephone($telephone)
    {
        return $this->findUser(['telephone' => $telephone])->one();
    }

    public function findUserByUsernameOrEmailOrTelephone($usernameOrEmailOrTelephone)
    {
        $res = $this->findUserByUsernameOrEmail($usernameOrEmailOrTelephone);

        if (empty($res)) {
            $res = $this->findUserByTelephone($usernameOrEmailOrTelephone);
        }

        return $res;
    }
}
