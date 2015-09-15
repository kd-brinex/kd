<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_131549_fk_orders extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m150914_131549_fk_orders cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropForeignKey('orders_part_provider_fk', 'orders');

    }

    public function safeDown()
    {
        $this->addForeignKey('orders_part_provider_fk', 'orders', 'provider_id', 'part_provider', 'id', 'RESTRICT', 'RESTRICT');

    }

}
