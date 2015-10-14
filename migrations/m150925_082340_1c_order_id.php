<?php

use yii\db\Schema;
use yii\db\Migration;

class m150925_082340_1c_order_id extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m150925_082340_1c_order_id cannot be reverted.\n";
//
//        return false;
//    }
    

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('order', '1c_order_id', Schema::TYPE_STRING.'(25)');
        $this->addColumn('order', 'comment', Schema::TYPE_STRING.'(100)');
    }
    
    public function safeDown()
    {
        $this->dropColumn('order', '1c_order_id');
        $this->dropColumn('order', 'comment');
    }

}
