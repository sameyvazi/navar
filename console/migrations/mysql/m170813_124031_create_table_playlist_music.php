<?php

use yii\db\Migration;

class m170813_124031_create_table_playlist_music extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( 'playlist_music', [
            'id' => $this->primaryKey(),
            'playlist_id' => $this->integer()->notNull(),
            'music_id' => $this->integer()->notNull(),
            'no' => $this->smallInteger(),
        ], $tableOptions);

        $this->createIndex('playlist_id', 'playlist_music', 'playlist_id');
        $this->createIndex('music_id', 'playlist_music', 'music_id');
        $this->createIndex('no', 'playlist_music', 'no');
    }

    public function safeDown()
    {
        $this->dropTable('playlist_music');

        return true;
    }
}
