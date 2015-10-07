<?php

use yii\db\Schema;
use yii\db\Migration;

class m150729_082816_reorder_button extends Migration
{
    public function up()
    {
        $this->addColumn('basket', 'tovar_article', Schema::TYPE_STRING.'(32)');
        $this->addColumn('orders', 'product_article', Schema::TYPE_STRING.'(32)');
    }

    public function down()
    {
        $this->dropColumn('basket', 'tovar_article');
        $this->dropColumn('orders', 'product_article');
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
