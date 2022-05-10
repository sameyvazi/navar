<?php

use yii\db\Migration;
use common\models\user\User;

class m180116_135216_add_column_premuim_table_users extends Migration
{
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'premium_date', $this->string());
        $this->addColumn(User::tableName(), 'invite_code', $this->string());

        $this->createIndex('premium_date', User::tableName(), 'premium_date');
        $this->createIndex('invite_code', User::tableName(), 'invite_code');
    }

    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'premium_date');
        $this->dropColumn(User::tableName(), 'invite_code');
    }
}
