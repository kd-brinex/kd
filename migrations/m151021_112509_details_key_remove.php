<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_112509_details_key_remove extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropIndex('unique_name', 'part_provider');
    }

    public function safeDown()
    {
        $this->createIndex('unique_name', 'part_provider', 'name', true);
    }
}
