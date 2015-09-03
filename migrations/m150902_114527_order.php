<?php

use yii\db\Schema;
use yii\db\Migration;

class m150902_114527_order extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'order_id', Schema::TYPE_INTEGER);
        $this->alterColumn('orders','product_id',Schema::TYPE_STRING.'(9) NULL');
        $this->alterColumn('orders','product_article',Schema::TYPE_STRING.'(32) NULL');

        $this->createTable('order',[
            'id' => Schema::TYPE_PK,
            'number' => Schema::TYPE_STRING.'(25)',
            'date' => Schema::TYPE_DATETIME,
            'user_id' => Schema::TYPE_INTEGER
        ]);

        $this->createTable('order_pay',[
            'id' => Schema::TYPE_PK,
            'order_id' => Schema::TYPE_INTEGER,
            'date' => Schema::TYPE_DATETIME,
            'sum' => Schema::TYPE_DOUBLE
        ]);

        $this->addForeignKey('orders_order_fk', 'orders', 'order_id', 'order', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('order_user_fk', 'order', 'user_id', 'user', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('order_pay_order_fk', 'order_pay', 'order_id', 'order', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('orders_order_fk', 'orders');
        $this->dropForeignKey('order_user_fk', 'order');
        $this->dropForeignKey('order_pay_order_fk', 'order_pay');
        $this->dropColumn('orders','order_id');
        $this->dropTable('order');
        $this->dropTable('order_pay');
        echo "m150902_114527_order cannot be reverted.\n";
        return true;
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
