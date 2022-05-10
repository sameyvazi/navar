<?php

use yii\db\Migration;
use common\models\like\Like;

class m170809_075604_add_column_post_type_table_likes extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Like::tableName(), 'post_type', $this->smallInteger());

        $this->createIndex('post_type', Like::tableName(), 'post_type');

    }

    public function safeDown()
    {
        $this->dropIndex('post_type', Like::tableName());

        $this->dropColumn(Like::tableName(), 'post_type');
    }
}
