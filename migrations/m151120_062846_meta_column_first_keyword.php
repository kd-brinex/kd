<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_062846_meta_column_first_keyword extends Migration
{
    public function up()
    {
        $this->addColumn('meta', 'first_keyword', Schema::TYPE_STRING.'(100)');
        $this->dropTable("meta_infotext");
        $this->createTable('{{%meta_infotext}}', [
            'id' => Schema::TYPE_PK,
            'meta_id' => Schema::TYPE_INTEGER. ' unsigned NOT NULL',
            'city_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'infotext_before' => Schema::TYPE_TEXT,
            'infotext_after' => Schema::TYPE_TEXT
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->addForeignKey('meta_infotext_meta_fk', 'meta_infotext', 'meta_id', 'meta', 'id_meta', 'CASCADE', 'CASCADE');
        $this->createIndex('meta_city_id', 'meta_infotext', ['meta_id','city_id'],true);
        $this->createIndex('meta_id_idx', 'meta_infotext', ['meta_id']);
    }

    public function down()
    {
        $this->dropTable("meta_infotext");
        $this->createTable('{{%meta_infotext}}', [
            'meta_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'city_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'infotext_before' => Schema::TYPE_TEXT,
            'infotext_after' => Schema::TYPE_TEXT,
            0 => 'PRIMARY KEY (`meta_id`,`city_id`)'
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->dropColumn('meta', 'first_keyword');

        return false;
    }
}
