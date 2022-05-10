<?php

use yii\db\Migration;
use common\models\mood\Mood;

class m180102_140227_add_column_key_table_mood extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Mood::tableName(), 'key', $this->string());
        $this->createIndex('key', Mood::tableName(), 'key');
    }

    public function safeDown()
    {
        $this->dropColumn(Mood::tableName(), 'key');
    }
}
