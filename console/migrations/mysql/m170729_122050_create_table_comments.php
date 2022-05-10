<?php

use yii\db\Migration;
use common\models\comment\Comment;

class m170729_122050_create_table_comments extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Comment::tableName(), [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'author_name' => $this->string(),
            'author_email' => $this->string(),
            'author_ip' => $this->string(),
            'content' => $this->text(),
            'like' => $this->integer()->defaultValue(0),
            'parent_comment_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Comment::STATUS_DISABLED),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('post_id', Comment::tableName(), 'post_id');
        $this->createIndex('type', Comment::tableName(), 'type');
        $this->createIndex('parent_comment_id', Comment::tableName(), 'parent_comment_id');
        $this->createIndex('status', Comment::tableName(), 'status');
        $this->createIndex('like', Comment::tableName(), 'like');
        $this->createIndex('created_at', Comment::tableName(), 'created_at');
    }

    public function down()
    {
        $this->dropTable(Comment::tableName());
    }
}
