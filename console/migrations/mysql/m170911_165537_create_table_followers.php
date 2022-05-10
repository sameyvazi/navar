<?php

use yii\db\Migration;
use common\models\follower\Follower;

class m170911_165537_create_table_followers extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Follower::tableName(), [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'post_type' => $this->smallInteger()->notNull(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('post_id', Follower::tableName(), 'post_id');
        $this->createIndex('post_type', Follower::tableName(), 'post_type');
        $this->createIndex('user_id', Follower::tableName(), 'user_id');
    }

    public function safeDown()
    {
        $this->dropTable(Follower::tableName());
    }
}
