<?php

use yii\db\Migration;
use common\models\tag\Tag;

class m170321_142140_create_table_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(Tag::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(Tag::tableName());
    }
}
