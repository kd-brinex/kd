<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_070025_1c extends Migration
{
    public function up()
    {
    $this->dropForeignKey('fk_ou1c_id','order_update_1c');
        $this->renameColumn('order_update_1c','order_id','OrderId');
        $this->renameTable('order_update_1c','OrderUpdate1c');
        return true;
    }

    public function down()
    {
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
