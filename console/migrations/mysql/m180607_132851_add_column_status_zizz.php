<?php

use yii\db\Migration;
use common\models\music\Music;
use common\models\artist\Artist;

class m180607_132851_add_column_status_zizz extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'status_zizz', $this->smallInteger()->defaultValue(Music::STATUS_DISABLED)->notNull());
        $this->addColumn(Artist::tableName(), 'status_zizz', $this->smallInteger()->defaultValue(Artist::STATUS_DISABLED)->notNull());

        $this->createIndex('status_zizz', Music::tableName(), 'status_zizz');
        $this->createIndex('status_zizz', Artist::tableName(), 'status_zizz');
    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'status_site');
        $this->dropColumn(Artist::tableName(), 'status_site');
    }
}
