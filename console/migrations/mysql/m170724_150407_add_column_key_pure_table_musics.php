<?php

use yii\db\Migration;
use common\models\music\Music;

class m170724_150407_add_column_key_pure_table_musics extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'key_pure', $this->string());

        $this->createIndex('key_pure', Music::tableName(), 'key');
    }

    public function safeDown()
    {
        $this->dropIndex('key_pure', Music::tableName());

        $this->dropColumn(Music::tableName(), 'key_pure');
    }
}
