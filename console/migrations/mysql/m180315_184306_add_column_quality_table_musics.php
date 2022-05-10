<?php

use yii\db\Migration;
use common\models\music\Music;

class m180315_184306_add_column_quality_table_musics extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'hd', $this->smallInteger()->notNull());
        $this->addColumn(Music::tableName(), 'hq', $this->smallInteger()->notNull());
        $this->addColumn(Music::tableName(), 'lq', $this->smallInteger()->notNull());

        $this->createIndex('hd', Music::tableName(), 'hd');
        $this->createIndex('hq', Music::tableName(), 'hq');
        $this->createIndex('lq', Music::tableName(), 'lq');
    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'hd');
        $this->dropColumn(Music::tableName(), 'hq');
        $this->dropColumn(Music::tableName(), 'lq');
    }
}
