<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_101918_orders_related_detail_column extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m151006_101918_orders_related_detail_column cannot be reverted.\n";
//
//        return false;
//    }
//

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('orders', 'related_detail', Schema::TYPE_INTEGER);
    }
    
    public function safeDown()
    {
        $this->dropColumn('orders', 'related_detail');
    }
}
