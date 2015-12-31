<?php

use yii\db\Schema;
use yii\db\Migration;

class m151230_065012_import_1c extends Migration
{
    public function up()
    {
        $this->addColumn('orders','1c_orders_id',Schema::TYPE_INTEGER);
        $this->createIndex('unique_1c_order_id', 'order', '1c_order_id', true);
        $this->createIndex('unique_1c_orders_id', 'orders', ['order_id','1c_orders_id'], true);
    }

    public function down()
    {
        $this->dropIndex('unique_1c_orders_id', 'orders');
        $this->dropIndex('unique_1c_order_id', 'order');
        $this->dropColumn('orders','1c_orders_id');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
