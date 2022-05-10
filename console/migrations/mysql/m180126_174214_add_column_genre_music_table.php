<?php

use yii\db\Migration;
use common\models\music\Music;

class m180126_174214_add_column_genre_music_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'genre_id', $this->smallInteger()->defaultValue(Music::GENRE_PERSIAN));

        $this->createIndex('genre_id', Music::tableName(), 'genre_id');
    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'genre_id');
    }
}
