<?php

use yii\db\Migration;
use common\models\contact\Contact;

class m180113_195538_add_column_subject_table_contact extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Contact::tableName(), 'subject', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(Contact::tableName(), 'subject');
    }
}
