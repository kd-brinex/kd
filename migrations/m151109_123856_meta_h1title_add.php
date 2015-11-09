<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_123856_meta_h1title_add extends Migration
{
    public function up()
    {
        $this->addColumn('meta', 'h1_title', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('meta', 'h1_title');

        return false;
    }
}
