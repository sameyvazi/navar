<?php

namespace console\controllers\artist;

use common\models\artist\Artist;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Index extends Action {

    public function run() {
        
        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Artists started...\n", Console::FG_YELLOW);

        $artists = Yii::$app->temp->createCommand("SELECT * FROM tbl_artist")->queryAll();
        foreach ($artists as $artist){

            switch ($artist['act']){
                case 'singer':
                    $act = Artist::TYPE_SINGER;
                    break;
                case 'composer':
                    $act = Artist::TYPE_COMPOSER;
                    break;
                case 'lyric':
                    $act = Artist::TYPE_LYRIC;
                    break;
                case 'arrangement':
                    $act = Artist::TYPE_REGULATOR;
                    break;
                case 'mix-mastering':
                    $act = Artist::TYPE_MONTAGE;
                    break;
                case 'director':
                    $act = Artist::TYPE_DIRECTOR;
                    break;
            }

            $params = [':id' => $artist['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT `key`,`key_fa` FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $model = Artist::find()->where(['key' => $meta['key']])->one();


            if (is_null($model)){
                $model = new Artist();
            }

            $model->name = $artist['name'];
            $model->name_fa = $artist['name_fa'];
            $model->activity = $act;
            $model->image = $artist['image'];
            $model->like = $artist['like'];
            $model->like_fa = $artist['like_fa'];
            $model->status = $artist['status'];
            $model->status_fa = $artist['status_fa'];
            $model->status_app = Artist::STATUS_ACTIVE;
            $model->key = $meta['key'];
            $model->key_fa = $meta['key_fa'];
            $model->user_id = 1;
            $model->save(false);

        }

        $this->controller->stdout("The transfer of the Artists is over.\n", Console::FG_GREEN);
        
    }

}
