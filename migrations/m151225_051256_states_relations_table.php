<?php

use yii\db\Schema;
use yii\db\Migration;

class m151225_051256_states_relations_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('state_relation', [
            'inner_state_id' => Schema::TYPE_SMALLINT.'(2) UNSIGNED NOT NULL',
            'provider_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'provider_state_id' => Schema::TYPE_INTEGER.' NOT NULL'
        ]);

        $this->addPrimaryKey('pk_prvdr_id_prvdr_stt_id', 'state_relation', ['provider_id', 'provider_state_id']);
        $this->addForeignKey('fk_innr_stt_id', 'state_relation', 'inner_state_id', 'order_states', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_prvdr_id', 'state_relation', 'provider_id', 'part_provider', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_prvdr_stt_id', 'state_relation', ['provider_id', 'provider_state_id'], 'provider_state_code', ['provider_id', 'status_code'], 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_prvdr_stt_id', 'state_relation');
        $this->dropForeignKey('fk_prvdr_id', 'state_relation');
        $this->dropForeignKey('fk_innr_stt_id', 'state_relation');
        $this->dropPrimaryKey('pk_prvdr_id_prvdr_stt_id', 'state_relation');
        $this->dropTable('state_relation');
    }
}
