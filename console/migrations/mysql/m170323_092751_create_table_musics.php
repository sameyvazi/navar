<?php

use yii\db\Migration;
use common\models\music\Music;

class m170323_092751_create_table_musics extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(Music::tableName(), [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull(),
            'key_fa' => $this->string()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'name' => $this->string()->notNull(),
            'name_fa' => $this->string()->notNull(),
            'artist_id' => $this->integer(),
            'special' => $this->smallInteger(),
            'music_id' => $this->integer(),
            'music_no' => $this->smallInteger(),
            'lyric' => $this->text(),
            'note' => $this->text(),
            'note_fa' => $this->text(),
            'note_app' => $this->text(),
            'like' => $this->integer()->defaultValue(0),
            'like_fa' => $this->integer()->defaultValue(0),
            'like_app' => $this->integer()->defaultValue(0),
            'view' => $this->integer()->defaultValue(0),
            'view_fa' => $this->integer()->defaultValue(0),
            'view_app' => $this->integer()->defaultValue(0),
            'play' => $this->integer()->defaultValue(0),
            'play_fa' => $this->integer()->defaultValue(0),
            'play_app' => $this->integer()->defaultValue(0),
            'time' => $this->string(),
            'directory' => $this->string(),
            'dl_link' => $this->string(),
            'image' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'status_fa' => $this->smallInteger()->notNull(),
            'status_app' => $this->smallInteger()->notNull(),

        ], $tableOptions);

        $this->createIndex('key', Music::tableName(), 'key');
        $this->createIndex('key_fa', Music::tableName(), 'key_fa');
        $this->createIndex('type', Music::tableName(), 'type');
        $this->createIndex('artist_id', Music::tableName(), 'artist_id');
        $this->createIndex('special', Music::tableName(), 'special');
        $this->createIndex('music_id', Music::tableName(), 'music_id');
        $this->createIndex('like', Music::tableName(), 'like');
        $this->createIndex('like_fa', Music::tableName(), 'like_fa');
        $this->createIndex('like_app', Music::tableName(), 'like_app');
        $this->createIndex('view', Music::tableName(), 'view');
        $this->createIndex('view_fa', Music::tableName(), 'view_fa');
        $this->createIndex('view_app', Music::tableName(), 'view_app');
        $this->createIndex('status', Music::tableName(), 'status');
        $this->createIndex('status_fa', Music::tableName(), 'status_fa');
        $this->createIndex('status_app', Music::tableName(), 'status_app');
        $this->createIndex('play', Music::tableName(), 'play');
        $this->createIndex('play_fa', Music::tableName(), 'play_fa');
        $this->createIndex('play_app', Music::tableName(), 'play_app');
        $this->createIndex('created_at', Music::tableName(), 'created_at');
    }

    public function down()
    {
        $this->dropTable(Music::tableName());
    }
}
