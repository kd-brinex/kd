<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_055008_order_pay extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m151006_055008_order_pay cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    $this->addColumn('order_pay','order_number',Schema::TYPE_STRING.'(25)');
    $this->addColumn('order_pay','description',Schema::TYPE_STRING.'(250)');
    }

    public function safeDown()
    {
        $this->dropColumn('order_pay','description');
        $this->dropColumn('order_pay','order_number');
    }

}
