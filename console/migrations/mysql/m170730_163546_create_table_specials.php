<?php

use yii\db\Migration;
use common\models\special\Special;

class m170730_163546_create_table_specials extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( Special::tableName(), [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'position' => $this->smallInteger()->notNull(),
            'music_id' =>$this->integer()->notNull(),
            'no' => $this->smallInteger()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('type', Special::tableName(), 'type');
        $this->createIndex('position', Special::tableName(), 'position');
        $this->createIndex('no', Special::tableName(), 'no');
        $this->createIndex('created_at', Special::tableName(), 'created_at');
    }

    public function down()
    {
        $this->dropTable(Special::tableName());
    }
}
