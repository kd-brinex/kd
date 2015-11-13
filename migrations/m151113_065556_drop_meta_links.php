<?php

use yii\db\Schema;
use yii\db\Migration;

class m151113_065556_drop_meta_links extends Migration
{
    public function up()
    {
        $this->dropTable('{{%meta_links}}');
    }

    public function down()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%meta_links}}', [
            'keyword' => 'VARCHAR(255) NOT NULL',
            'link' => 'VARCHAR(255) NOT NULL',
            'seq_number' => 'INT(11) NOT NULL',
            'infotext_after' => 'TEXT NULL',
            0 => 'PRIMARY KEY (`keyword`)'
        ], $tableOptions);
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
