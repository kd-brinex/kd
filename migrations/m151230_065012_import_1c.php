<?php

use yii\db\Schema;
use yii\db\Migration;

class m151230_065012_import_1c extends Migration
{
    public function up()
    {
    $this->createIndex('unique_1c_order_id','order','1c_order_id',true);
    $this->createIndex('unique_orders','orders',['order_id','product_id','product_article'],true);
    }

    public function down()
    {
    $this->dropIndex('unique_orders','orders');
    $this->dropIndex('unique_1c_order_id','order');
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
