<?php

use yii\db\Migration;
use common\models\playlist\Playlist;

class m170814_141950_add_column_public_table_playlist extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Playlist::tableName(), 'public', $this->smallInteger());

        $this->createIndex('public', Playlist::tableName(), 'public');

    }

    public function safeDown()
    {
        $this->dropIndex('public', Playlist::tableName());

        $this->dropColumn(Playlist::tableName(), 'public');
    }
}
