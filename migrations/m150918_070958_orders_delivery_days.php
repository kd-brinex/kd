<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_070958_orders_delivery_days extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m150918_070958_orders_delivery_days cannot be reverted.\n";
//
//        return false;
//    }
    
//    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('orders', 'delivery_days', Schema::TYPE_INTEGER);
    }
    
    public function safeDown()
    {
        $this->dropColumn('orders', 'delivery_days', Schema::TYPE_INTEGER);
    }
//    */
}
