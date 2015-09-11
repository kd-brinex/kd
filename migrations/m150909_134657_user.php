<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_134657_user extends Migration
{
    /*
    public function up()
    {

    }

    public function down()
    {
        echo "m150909_134657_user cannot be reverted.\n";

        return false;
    }
*/

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('user','telephone',Schema::TYPE_STRING.'(20)');
        $this->addColumn('user','user_id_1c',Schema::TYPE_STRING.'(30)');
        $this->dropColumn('profile','telephone');
    }

    public function safeDown()
    {
        $this->addColumn('profile','telephone',Schema::TYPE_STRING.'(15)');
        $this->dropColumn('user','telephone');
        $this->dropColumn('user','user_id_1c');
    }

}
