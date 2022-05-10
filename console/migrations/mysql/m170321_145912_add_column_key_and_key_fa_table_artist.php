<?php

use yii\db\Migration;
use common\models\artist\Artist;

class m170321_145912_add_column_key_and_key_fa_table_artist extends Migration
{
    public function up()
    {
        $this->addColumn(Artist::tableName(), 'key', $this->string());
        $this->addColumn(Artist::tableName(), 'key_fa', $this->string());

        $this->createIndex('key', Artist::tableName(), 'key');
        $this->createIndex('key_fa', Artist::tableName(), 'key_fa');
    }

    public function down()
    {
        $this->dropIndex('key', Artist::tableName());
        $this->dropIndex('key_fa', Artist::tableName());

        $this->dropColumn(Artist::tableName(), 'key');
        $this->dropColumn(Artist::tableName(), 'key_fa');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
