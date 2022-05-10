<?php

use yii\db\Migration;
use common\models\music\MusicArtist;

class m170328_075700_create_table_music_artist extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(MusicArtist::tableName(), [
            'id' => $this->primaryKey(),
            'music_id' => $this->integer()->notNull(),
            'artist_id' => $this->integer()->notNull(),
            'activity' => $this->smallInteger()->notNull()
        ], $tableOptions);

        $this->createIndex('music_id', MusicArtist::tableName(), 'music_id');
        $this->createIndex('artist_id', MusicArtist::tableName(), 'artist_id');
    }

    public function safeDown()
    {
        $this->dropTable(MusicArtist::tableName());
    }
}
