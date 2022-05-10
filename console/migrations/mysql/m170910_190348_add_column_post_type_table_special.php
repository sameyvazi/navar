<?php

use yii\db\Migration;
use common\models\special\Special;

class m170910_190348_add_column_post_type_table_special extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Special::tableName(), 'post_type', $this->smallInteger());

        $this->renameColumn(Special::tableName(), 'music_id', 'post_id');

        $this->createIndex('post_type', Special::tableName(), 'post_type');
        $this->createIndex('post_id', Special::tableName(), 'post_id');
    }

    public function safeDown()
    {
        $this->dropColumn(Special::tableName(), 'post_type');
    }
}
