<?php

use yii\db\Schema;
use yii\db\Migration;

class m150904_071131_file_upload extends Migration
{
    public function safeUp()
    {
        $this->createTable('img_image', [
            'id' => Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT',
            'table' => Schema::TYPE_STRING,
            'table_id' => Schema::TYPE_INTEGER,
            'src' => Schema::TYPE_STRING,
            'title' => Schema::TYPE_STRING,
            'alt' => Schema::TYPE_STRING,
        ],'CHARACTER SET utf8 COLLATE utf8_general_ci');
    }

    public function safeDown()
    {
        echo "m150904_071131_file_upload cannot be reverted.\n";

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
