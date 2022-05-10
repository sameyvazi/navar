<?php

namespace console\controllers\tag;

use common\models\artist\Artist;
use common\models\tag\Tag;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Artists extends Action {

    public function run() {
        
        Yii::$app->language = 'en';
        $this->controller->stdout("Tag of Artists started...\n", Console::FG_YELLOW);


        $artists = Artist::find()->select(['id', 'name', 'name_fa', 'key', 'key_fa'])->all();

        foreach($artists as $model){

            $tags = [];

            $tags[] = str_replace(' ', '-', $model->name);
            $tags[] = str_replace(' ', '-', $model->name_fa);
            $tags[] = $model->key;
            $tags[] = $model->key_fa;

            \Yii::$app->tags->hashtag($tags, Tag::TYPE_ARTIST, $model->id);

            echo $model->id.'-';
        }

        $this->controller->stdout("The Tag of the Artists is over.\n", Console::FG_GREEN);
        
    }

}
