<?php
namespace app\modules\catalog\models;


/**
 * Created by PhpStorm.
 * User: marat
 * Date: 24.06.15
 * Time: 10:12
 */
class ToyotaQuery extends AvQuery
{
    public function getFields()
    {
        return [
            'name',
            'model_name',
            'catalog',
            'catalog_code',
            'model_code',
            'sysopt',
            'compl_code',
            'part_group',
            'rec_num'
        ];
    }




}