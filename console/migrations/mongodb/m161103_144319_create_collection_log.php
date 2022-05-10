<?php

class m161103_144319_create_collection_log extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection('log');
        $this->createIndex('log', ['log_time' => -1]);
        
        return true;
    }

    public function down()
    {
        $this->dropCollection('log');

        return true;
    }
}
