<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_064326_basket_provider_id extends Migration
{
//    public function up()
//    {
//        $this->
//    }
//
//    public function down()
//    {
//        echo "m150904_064326_basket_provider_id cannot be reverted.\n";
//
//        return true;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('basket','provider_id',Schema::TYPE_INTEGER);
    }

    public function safeDown()
    {
        $this->dropColumn('basket','provider_id');
    }

}
