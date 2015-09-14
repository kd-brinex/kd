<?php

use yii\db\Schema;
use yii\db\Migration;

class m150910_115629_order_1c extends Migration
{
    /*
    public function up()
    {

    }

    public function down()
    {
        echo "m150910_115629_order_1c cannot be reverted.\n";

        return false;
    }
*/

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('order','f_1c',Schema::TYPE_INTEGER);
      }

    public function safeDown()
    {
        $this->dropColumn('order','f_1c');
    }

}
