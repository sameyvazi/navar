<?php

use yii\db\Migration;
use common\models\tag\Tag;

class m171010_064445_add_column_created_at_table_tags extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Tag::tableName(), 'created_at', $this->integer());

        $this->createIndex('created_at', Tag::tableName(), 'created_at');
        $this->createIndex('name', Tag::tableName(), 'name');
    }

    public function safeDown()
    {
        $this->dropColumn(Tag::tableName(), 'created_at');
    }
}
