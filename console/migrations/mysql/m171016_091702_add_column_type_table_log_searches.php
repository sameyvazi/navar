<?php

use yii\db\Migration;
use common\models\log\LogSearch;

class m171016_091702_add_column_type_table_log_searches extends Migration
{
    public function safeUp()
    {
        $this->addColumn(LogSearch::tableName(), 'type', $this->smallInteger());

        $this->createIndex('type', LogSearch::tableName(), 'type');
    }

    public function safeDown()
    {
        $this->dropColumn(LogSearch::tableName(), 'type');
    }
}
