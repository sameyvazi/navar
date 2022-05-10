<?php

use yii\db\Migration;
use common\models\music\Music;

class m170826_103107_add_column_title_table_music extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'title_en', $this->string());
        $this->addColumn(Music::tableName(), 'title_fa', $this->string());

    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'title_en');
        $this->dropColumn(Music::tableName(), 'title_fa');
    }

}
