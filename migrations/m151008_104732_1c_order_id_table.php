<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_104732_1c_order_id_table extends Migration
{
    /*
    public function up()
    {

    }

    public function down()
    {
        echo "m151008_104732_1c_order_id_table cannot be reverted.\n";

        return false;
    }
    */

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('order_update_1c', ['order_id' => Schema::TYPE_PK]);
        $this->addForeignKey('fk_ou1c_id', 'order_update_1c', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('order_update_1c');
    }

}
