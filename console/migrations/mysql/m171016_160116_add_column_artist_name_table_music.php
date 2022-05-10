<?php

use yii\db\Migration;
use common\models\music\Music;

class m171016_160116_add_column_artist_name_table_music extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'artist_name', $this->string());
        $this->addColumn(Music::tableName(), 'artist_name_fa', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'artist_name');
        $this->dropColumn(Music::tableName(), 'artist_name_fa');
    }
}
