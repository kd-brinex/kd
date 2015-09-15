<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_064158_providers_module extends Migration
{
    public function up()
    {
        $this->alterColumn('part_provider_user', 'login', Schema::TYPE_STRING.'(15) NULL');
        $this->alterColumn('part_provider_user', 'password', Schema::TYPE_STRING.'(64)');
    }

    public function down()
    {
        $this->alterColumn('part_provider_user', 'login', Schema::TYPE_STRING.'(15) NOT NULL');
        $this->alterColumn('part_provider_user', 'password', Schema::TYPE_STRING.'(15)');
        echo "m150914_064158_providers_module cannot be reverted.\n";
        return false;
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
