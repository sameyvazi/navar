<?php

use yii\db\Migration;

class m170328_132949_create_table_tag_relation extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tag_relations', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull()
        ], $tableOptions);

        $this->createIndex('post_id', 'tag_relations', 'post_id');
        $this->createIndex('tag_id', 'tag_relations', 'tag_id');
    }

    public function safeDown()
    {
        $this->dropTable('tag_relations');
    }
}
