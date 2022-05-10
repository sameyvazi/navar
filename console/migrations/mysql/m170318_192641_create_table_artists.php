<?php

use yii\db\Migration;
use common\models\artist\Artist;

class m170318_192641_create_table_artists extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(Artist::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_fa' => $this->string()->notNull(),
            'activity' => $this->smallInteger()->notNull(),
            'image' => $this->string(),
            'like' => $this->integer()->defaultValue(0),
            'like_fa' => $this->integer()->defaultValue(0),
            'like_app' => $this->integer()->defaultValue(0),
            'status' => $this->smallInteger()->notNull(),
            'status_fa' => $this->smallInteger()->notNull(),
            'status_app' => $this->smallInteger()->notNull(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('like', Artist::tableName(), 'like');
        $this->createIndex('like_fa', Artist::tableName(), 'like_fa');
        $this->createIndex('like_app', Artist::tableName(), 'like_app');
        $this->createIndex('status', Artist::tableName(), 'status');
        $this->createIndex('status_fa', Artist::tableName(), 'status_fa');
        $this->createIndex('status_app', Artist::tableName(), 'status_app');
        $this->createIndex('activity', Artist::tableName(), 'activity');
        $this->createIndex('created_at', Artist::tableName(), 'created_at');
    }

    public function down()
    {
        $this->dropTable(Artist::tableName());
    }
}
