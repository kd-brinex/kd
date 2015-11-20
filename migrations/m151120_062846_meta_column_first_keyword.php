<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_062846_meta_column_first_keyword extends Migration
{
    public function up()
    {
        $this->addColumn('meta', 'first_keyword', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('meta', 'first_keyword');
        return false;
    }
}
