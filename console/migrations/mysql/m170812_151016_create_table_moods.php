<?php

use yii\db\Migration;
use common\models\mood\Mood;

class m170812_151016_create_table_moods extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Mood::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_fa' => $this->string()->notNull(),
            'image' => $this->string(),
            'no' => $this->smallInteger(),
            'status' => $this->smallInteger()->notNull(),
            'status_fa' => $this->smallInteger()->notNull(),
            'status_app' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('no', Mood::tableName(), 'no');
        $this->createIndex('status', Mood::tableName(), 'status');
        $this->createIndex('status_fa', Mood::tableName(), 'status_fa');
        $this->createIndex('status_app', Mood::tableName(), 'status_app');
        $this->createIndex('created_at', Mood::tableName(), 'created_at');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable(Mood::tableName());

        return true;
    }
}
