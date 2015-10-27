<?php

use yii\db\Schema;
use yii\db\Migration;

class m151023_060323_order_provider_id_column extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('provider_state_code', [
            'provider_id' => Schema::TYPE_INTEGER,
            'status_code' => Schema::TYPE_INTEGER,
            'status_name' => Schema::TYPE_STRING
        ]);

        $this->addPrimaryKey('prvdr_stt_cd_pk', 'provider_state_code', ['provider_id', 'status_code']);

        $this->addColumn('orders', 'order_provider_id', Schema::TYPE_STRING);
        $this->addColumn('orders', 'order_provider_status', Schema::TYPE_INTEGER);


        $this->addForeignKey('ordrs_ordr_prvdr_stts_fk', 'orders', ['provider_id', 'order_provider_status'], 'provider_state_code', ['provider_id', 'status_code'], 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('ordrs_ordr_prvdr_stts_fk', 'orders');

        $this->dropColumn('orders', 'order_provider_status');
        $this->dropColumn('orders', 'order_provider_id');

        $this->dropPrimaryKey('prvdr_stt_cd_pk', 'provider_state_code');

        $this->dropTable('provider_state_code');
    }
}
