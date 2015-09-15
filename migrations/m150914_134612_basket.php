<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_134612_basket extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m150914_134612_basket cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropForeignKey('orders_t_stor_fk','orders');
        $this->dropIndex('orders_t_store_i','orders');
        $this->dropIndex('ordrs_uid_i','orders');
        $this->dropColumn('orders','store_id');
        $this->dropColumn('orders','location');
        $this->dropColumn('orders','name');
        $this->dropColumn('orders','email');
        $this->dropColumn('orders','telephone');
        $this->dropColumn('orders','uid');

        $this->addColumn('order','user_name',Schema::TYPE_STRING.'(25)');
        $this->addColumn('order','user_email',Schema::TYPE_STRING.'(25)');
        $this->addColumn('order','user_telephone',Schema::TYPE_STRING.'(25)');
        $this->addColumn('order','user_location',Schema::TYPE_STRING.'(25)');
        $this->addColumn('order','store_id',Schema::TYPE_INTEGER);

        $this->dropForeignKey('order_user_fk','order');
    }

    public function safeDown()
    {
        $this->addColumn('orders','store_id',Schema::TYPE_INTEGER);
        $this->addColumn('orders','location',Schema::TYPE_STRING.'(25)');
        $this->addColumn('orders','name',Schema::TYPE_STRING.'(50)');
        $this->addColumn('orders','email',Schema::TYPE_STRING.'(25)');
        $this->addColumn('orders','telephone',Schema::TYPE_STRING.'(20)');
        $this->addColumn('orders','uid',Schema::TYPE_INTEGER);
        $this->createIndex('orders_t_store_i', 'orders', ['store_id'], false);
        $this->createIndex('ordrs_uid_i', 'orders', ['uid'], false);
        $this->addForeignKey('orders_t_stor_fk','orders','store_id','t_store','id', 'RESTRICT', 'RESTRICT');

        $this->dropColumn('order','user_name');
        $this->dropColumn('order','user_email');
        $this->dropColumn('order','user_telephone');
        $this->dropColumn('order','user_location');
        $this->dropColumn('order','store_id');

        $this->addForeignKey('order_user_fk','order','user_id','user','id','RESTRICT','RESTRICT');
    }

}
