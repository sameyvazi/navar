<?php

use yii\db\Migration;
use common\models\contact\Contact;

class m171217_184726_create_table_contact_us extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Contact::tableName(), [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'author_name' => $this->string(),
            'author_email' => $this->string(),
            'content' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(2),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('type', Contact::tableName(), 'type');
        $this->createIndex('status', Contact::tableName(), 'status');
        $this->createIndex('created_at', Contact::tableName(), 'created_at');
    }

    public function safeDown()
    {
        $this->dropTable('contacts');
    }
}
