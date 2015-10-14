<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_130111_Orders_isPaid extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m150928_130111_Orders_isPaid cannot be reverted.\n";
//
//        return false;
//    }
    

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('orders', 'is_paid', Schema::TYPE_SMALLINT.'(1)');
    }
    
    public function safeDown()
    {
        $this->dropColumn('orders', 'is_paid');
    }

}
