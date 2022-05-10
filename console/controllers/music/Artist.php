<?php

namespace console\controllers\music;

use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Artist extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Artist started...\n", Console::FG_YELLOW);

        $artists = Yii::$app->temp->createCommand("SELECT * FROM tbl_artist_music")->queryAll();
        foreach ($artists as $artist){


            //find music
            $params = [':id' => $artist['mp3_id']];
            $musicMeta = Yii::$app->temp->createCommand("SELECT * FROM tbl_music_mp3 WHERE id=:id", $params)->queryOne();

            //find newMusic id
            $params = [':id' => $musicMeta['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $newMusic = Music::find()->where(['key_pure' => $meta['key'], 'type' => Music::TYPE_MP3])->one();


            //find artist
            $params = [':id' => $artist['artist_id']];
            $artistMeta = Yii::$app->temp->createCommand("SELECT * FROM tbl_artist WHERE id=:id", $params)->queryOne();


            //find artist id
            $params = [':id' => $artistMeta['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $newArtist = Artists::find()->where(['key' => $meta['key']])->one();


            switch ($artist['act']){
                case 'singer':
                    $act = [Artists::TYPE_SINGER, Artists::TYPE_MAIN_ARTIST];
                    break;
                case 'composer':
                    $act = Artists::TYPE_COMPOSER;
                    break;
                case 'lyric':
                    $act = Artists::TYPE_LYRIC;
                    break;
                case 'arrangement':
                    $act = Artists::TYPE_REGULATOR;
                    break;
                case 'mixMaster':
                    $act = Artists::TYPE_MONTAGE;
                    break;
                case 'director':
                    $act = Artists::TYPE_DIRECTOR;
                    break;
            }



            if ($newMusic && $newArtist){

                $model = MusicArtist::find()->where(['music_id' => $newMusic->id, 'artist_id' => $newMusic->artist_id, 'activity' => Artists::TYPE_MAIN_ARTIST])->one();

                if (!$model){
                    $model = new MusicArtist();
                    $model->music_id = $newMusic->id;
                    $model->artist_id = $newMusic->artist_id;
                    $model->activity = Artists::TYPE_MAIN_ARTIST;
                    $model->save();
                }


                $model = MusicArtist::find()
                    ->where(['music_id' => $newMusic->id, 'artist_id' => $newArtist->id])
                    ->andWhere(['in', 'activity', $act])
                    ->one();


                if (!$model){
                    $model = new MusicArtist();
                    $model->music_id = $newMusic->id;
                    $model->artist_id = $newArtist->id;
                    $model->activity = $act;
                    $model->save();
                }

            }
        }

        $this->controller->stdout("The transfer of the Artist is over.\n", Console::FG_GREEN);
    }

}
