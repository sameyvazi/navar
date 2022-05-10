<?php

use yii\db\Migration;
use common\models\music\Music;

class m171030_084246_add_index_column_table_music extends Migration
{
    public function safeUp()
    {
        $this->createIndex('name', Music::tableName(), 'name');
        $this->createIndex('name_fa', Music::tableName(), 'name_fa');
    }

    public function safeDown()
    {

    }
}
