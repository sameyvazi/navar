<?php

use yii\db\Migration;

class m170813_084432_create_table_playlists extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( 'playlists', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_fa' => $this->string()->notNull(),
            'mood_id' => $this->integer()->notNull(),
            'image' => $this->string(),
            'no' => $this->smallInteger(),
            'limit' => $this->smallInteger()->defaultValue(30),
            'status' => $this->smallInteger()->notNull(),
            'status_fa' => $this->smallInteger()->notNull(),
            'status_app' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('mood_id', 'playlists', 'mood_id');
        $this->createIndex('no', 'playlists', 'no');
        $this->createIndex('status', 'playlists', 'status');
        $this->createIndex('status_fa', 'playlists', 'status_fa');
        $this->createIndex('status_app', 'playlists', 'status_app');
        $this->createIndex('created_at', 'playlists', 'created_at');
        $this->createIndex('updated_at', 'playlists', 'updated_at');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('playlists');

        return true;
    }
}
