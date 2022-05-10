<?php

use yii\db\Migration;
use common\models\user\User;

class m171017_080052_add_column_password_table_user extends Migration
{
    public function safeUp()
    {
        $this->alterColumn(User::tableName(), 'mobile', $this->string()->null());

        $this->addColumn(User::tableName(), 'password', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'password');
    }
}
