<?php

use yii\db\Migration;
use common\models\user\User;

class m170801_182355_create_table_users extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( User::tableName(), [
            'id' => $this->primaryKey(),
            'mobile' => $this->string()->notNull(),
            'email' => $this->string(),
            'username' => $this->string(),
            'name' => $this->string(),
            'lastname' => $this->string(),
            'gender' => $this->smallInteger(),
            'birthday' => $this->string(),
            'devices' => $this->string(),
            'activation_code' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
            'avatar' => $this->string(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('mobile', User::tableName(), 'mobile');
        $this->createIndex('email', User::tableName(), 'email');
        $this->createIndex('activation_code', User::tableName(), 'activation_code');
        $this->createIndex('status', User::tableName(), 'status');
        $this->createIndex('created_at', User::tableName(), 'created_at');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable(User::tableName());

        return true;
    }
}
