<?php

use yii\db\Migration;
use common\models\user\User;

class m171025_171136_add_column_reset_password_table_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'reset_password', $this->string());

        $this->createIndex('reset_password', User::tableName(), 'reset_password');


    }

    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'reset_password');
    }
}
