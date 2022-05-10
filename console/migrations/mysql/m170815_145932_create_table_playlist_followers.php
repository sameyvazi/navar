<?php

use yii\db\Migration;

class m170815_145932_create_table_playlist_followers extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( 'playlist_followers', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'playlist_id' => $this->integer()->notNull(),
            'no' => $this->smallInteger(),
        ], $tableOptions);

        $this->createIndex('playlist_id', 'playlist_followers', 'playlist_id');
        $this->createIndex('user_id', 'playlist_followers', 'user_id');

    }

    public function safeDown()
    {
        $this->dropTable('playlist_followers');

        return true;
    }
}
