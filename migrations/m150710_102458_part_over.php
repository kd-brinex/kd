<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_102458_part_over extends Migration
{
    public function up()
    {
        $this->createTable('part_over', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'manufacture' => Schema::TYPE_STRING,
            'price' => Schema::TYPE_FLOAT,
            'quantity' => Schema::TYPE_INTEGER,
            'srokmin' => Schema::TYPE_INTEGER,
            'srokmax' => Schema::TYPE_INTEGER,
            'lotquantity' => Schema::TYPE_INTEGER,
            'pricedate' => Schema::TYPE_DATE,
            'skladid' => Schema::TYPE_INTEGER,
            'sklad' => Schema::TYPE_STRING,
            'flagpostav' => Schema::TYPE_STRING,
            'date_update' => Schema::TYPE_TIMESTAMP.' DEFAULT NOW()' ,
        ],'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->createIndex('parts_unique','part_over',['code','name','flagpostav'],true);
    }
    public function down()
    {
        $this->dropTable('part_over');
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
