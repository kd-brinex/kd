<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_113949_basket extends Migration
{
    public function up()
    {
        $this->createTable('orders', [
            'id' => Schema::TYPE_PK,
            'uid' => Schema::TYPE_INTEGER. '(11) NOT NULL',
            'product_id' => Schema::TYPE_STRING. '(9) NOT NULL',
            'manufacture' => Schema::TYPE_STRING . '(45)',
            'part_name' => Schema::TYPE_STRING.'(255)',
            'part_price' => Schema::TYPE_DOUBLE. ' unsigned NOT NULL',
            'quantity' => Schema::TYPE_INTEGER. '(3) unsigned NOT NULL',
            'reference' => Schema::TYPE_STRING. '(50)',
            'status' => Schema::TYPE_SMALLINT. '(2) unsigned NOT NULL',
            'datetime' => Schema::TYPE_DATETIME. ' NOT NULL',
            'name' => Schema::TYPE_STRING.'(15)',
            'email' => Schema::TYPE_STRING.'(100)',
            'telephone' => Schema::TYPE_STRING.'(15)',
            'location' => Schema::TYPE_STRING.'(100)',
            'store_id' => Schema::TYPE_INTEGER.'(11)'. ' NOT NULL',
            'description' => Schema::TYPE_TEXT
        ],  'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->createIndex('ordrs_prdct_i', 'orders', ['product_id'], false);
        $this->createIndex('orders_stts_i', 'orders', ['status'], false);
        $this->createIndex('ordrs_uid_i', 'orders', ['uid'], false);
        $this->createIndex('orders_t_store_i', 'orders', ['store_id'], false);


        $this->createTable('order_states', [
            'id' => Schema::TYPE_SMALLINT. '(2) unsigned NOT NULL',
            'status_name' => Schema::TYPE_STRING.'(30) NOT NULL'
        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->addPrimaryKey('ordr_stts_id', 'order_states', 'id');

        $this->createTable('basket', [
            'id' => Schema::TYPE_PK,
            'tovar_id' => Schema::TYPE_STRING.'(9)',
            'tovar_count' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'tovar_price' => Schema::TYPE_DOUBLE.' NOT NULL',
            'session_id' => Schema::TYPE_STRING.'(45) NOT NULL',
            'tovar_min' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'uid' => Schema::TYPE_INTEGER.'(11)',
            'manufacturer' => Schema::TYPE_STRING.'(45)',
            'part_number' => Schema::TYPE_STRING.'(45)',
            'period' => Schema::TYPE_INTEGER.'(2)',
            'part_name' => Schema::TYPE_STRING.'(255)',
            'description' => Schema::TYPE_TEXT,
            'allsum' => Schema::TYPE_DOUBLE.' NOT NULL'

        ], 'CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->createIndex('index', 'basket', ['tovar_id', 'session_id'], true);
        $this->createIndex('basket_usri_2', 'basket', ['uid']);

        $this->addForeignKey('orders_sttss_fk','orders','status','order_states','id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('orders_t_stor_fk','orders','store_id','t_store','id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('basket_usrf_2', 'basket', 'uid', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->insert('order_states',
             [
                'id' => 1,
                'status_name' => 'В ОБРАБОТКЕ'
            ]
        );
    }

    public function down()
        {
        $this->dropTable('orders');
        $this->dropTable('order_states');
        $this->dropTable('basket');

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
