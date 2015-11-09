<?php

use yii\db\Schema;
use yii\db\Migration;

class m151103_124452_meta_infotext extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        /* MYSQL */
        $this->createTable('{{%meta_infotext}}', [
            'meta_id' => 'INT(11) NOT NULL',
            'city_id' => 'INT(11) NOT NULL',
            'infotext_before' => 'TEXT NULL',
            'infotext_after' => 'TEXT NULL',
            0 => 'PRIMARY KEY (`meta_id`,`city_id`)'
        ], $tableOptions);

        $this->addColumn('meta', 'infotext_before', Schema::TYPE_TEXT);
        $this->addColumn('meta', 'infotext_after', Schema::TYPE_TEXT);
        $this->dropColumn('meta', 'info');

        $this->createTable('{{%meta_links}}', [
            'keyword' => 'VARCHAR(255) NOT NULL',
            'link' => 'VARCHAR(255) NOT NULL',
            'seq_number' => 'INT(11) NOT NULL',
            'infotext_after' => 'TEXT NULL',
            0 => 'PRIMARY KEY (`keyword`)'
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%meta_infotext}}');
        $this->addColumn('meta', 'info', Schema::TYPE_TEXT);
        $this->dropTable('{{%meta_links}}');
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
