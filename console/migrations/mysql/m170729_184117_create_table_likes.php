<?php

use yii\db\Migration;
use common\models\like\Like;

class m170729_184117_create_table_likes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Like::tableName(), [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'author_ip' => $this->string(),
            'user_id' =>$this->integer(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('post_id', Like::tableName(), 'post_id');
        $this->createIndex('type', Like::tableName(), 'type');
        $this->createIndex('user_id', Like::tableName(), 'user_id');
    }

    public function down()
    {
        $this->dropTable(Like::tableName());
    }
}
