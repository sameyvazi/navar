<?php

use yii\db\Migration;
use common\models\music\MusicArtist;

class m171110_121504_add_column_music_type_table_music_artist extends Migration
{
    public function safeUp()
    {
        $this->addColumn(MusicArtist::tableName(), 'type', $this->integer());

        $this->createIndex('type', MusicArtist::tableName(), 'type');
    }

    public function safeDown()
    {
        $this->dropColumn(MusicArtist::tableName(), 'type');
    }
}
