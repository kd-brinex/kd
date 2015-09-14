<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_064158_providers_module extends Migration
{
    public function up()
    {
        $this->alterColumn('part_provider_user', 'login', Schema::TYPE_STRING.'(15) NULL');
        $this->alterColumn('part_provider_user', 'password', Schema::TYPE_STRING.'(64)');
        $this->insert('part_provider_user', [
            'name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            'login' => 'chelny-KD',
            'password' => '54lmmsWchelny',
            'store_id' => 4,
            'provider_id' => 2,
            'marga' => 15
        ]);
        $this->insert('part_provider_user', [
            'name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            'login' => 'navilevin1',
            'password' => 'iY3VnPN1tbCILD7kjlM74xhzKHLuDMYpoDX8L1CdP1uOv3VTzLf13YVjcTEL3iAY',
            'store_id' => 4,
            'provider_id' => 8,
            'marga' => 15
        ]);
        $this->insert('part_provider_user', [
            'name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            'password' => '62224996794244a125b9b3fd734f9dd75dc08d1a59b5a42268bbec9d3e8d7bc3',
            'store_id' => 4,
            'provider_id' => 9,
            'marga' => 15
        ]);
        $this->insert('part_provider_user', [
            'name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            'password' => 'F4E1BC3571D43FA3F596BFE463BD6BB5',
            'store_id' => 4,
            'provider_id' => 1,
            'marga' => 15
        ]);
    }

    public function down()
    {
        $this->delete('part_provider_user', 'name = :name AND password = :password AND store_id = :store_id AND provider_id = :provider_id AND marga = :marga',[
            ':name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            ':login' => 'chelny-KD',
            ':password' => '54lmmsWchelny',
            ':store_id' => 4,
            ':provider_id' => 2,
            ':marga' => 15
        ]);
        $this->delete('part_provider_user', 'name = :name AND password = :password AND store_id = :store_id AND provider_id = :provider_id AND marga = :marga',[
            ':name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            ':login' => 'navilevin1',
            ':password' => 'iY3VnPN1tbCILD7kjlM74xhzKHLuDMYpoDX8L1CdP1uOv3VTzLf13YVjcTEL3iAY',
            ':store_id' => 4,
            ':provider_id' => 8,
            ':marga' => 15
        ]);
        $this->delete('part_provider_user', 'name = :name AND password = :password AND store_id = :store_id AND provider_id = :provider_id AND marga = :marga',[
            ':name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            ':password' => '62224996794244a125b9b3fd734f9dd75dc08d1a59b5a42268bbec9d3e8d7bc3',
            ':store_id' => 4,
            ':provider_id' => 9,
            ':marga' => 15
        ]);
        $this->delete('part_provider_user', 'name = :name AND password = :password AND store_id = :store_id AND provider_id = :provider_id AND marga = :marga',[
            ':name' => 'РТ, Н.Челны, ул.Промышленная 93 (СКЛАД ЦС)',
            ':password' => 'F4E1BC3571D43FA3F596BFE463BD6BB5',
            ':store_id' => 4,
            ':provider_id' => 1,
            ':marga' => 15
        ]);

        $this->alterColumn('part_provider_user', 'login', Schema::TYPE_STRING.'(15) NOT NULL');
        $this->alterColumn('part_provider_user', 'password', Schema::TYPE_STRING.'(15)');
        echo "m150914_064158_providers_module cannot be reverted.\n";
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
