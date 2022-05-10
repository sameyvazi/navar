<?php

use yii\db\Migration;

class m170911_135409_create_table_log_search extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( 'log_searches', [
            'id' => $this->primaryKey(),
            'query' => $this->string(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->createIndex('query', 'log_searches', 'query');
    }

    public function safeDown()
    {
        $this->dropTable('log_searches');

        return true;
    }
}
