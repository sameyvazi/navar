<?php

use yii\db\Migration;
use common\models\music\Music;
use common\models\artist\Artist;

class m180218_110033_add_column_status_site_table_music_and_artist extends Migration
{
    public function safeUp()
    {
        $this->addColumn(Music::tableName(), 'status_site', $this->smallInteger()->notNull());
        $this->addColumn(Artist::tableName(), 'status_site', $this->smallInteger()->notNull());

        $this->createIndex('status_site', Music::tableName(), 'status_site');
        $this->createIndex('status_site', Artist::tableName(), 'status_site');

        ini_set('memory_limit', '-1');
        $msuics = Music::find()->all();
        foreach ($msuics as $music){
            $music->status_site = $music->status_app;
            $music->save();
        }

        $artists = Artist::find()->all();
        foreach ($artists as $artist){
            $artist->status_site = $artist->status_app;
            $artist->save();
        }
    }

    public function safeDown()
    {
        $this->dropColumn(Music::tableName(), 'status_site');
        $this->dropColumn(Artist::tableName(), 'status_site');
    }
}
