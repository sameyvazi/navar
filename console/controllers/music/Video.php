<?php

namespace console\controllers\music;

use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Video extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Video started...\n", Console::FG_YELLOW);

        $musics = Yii::$app->temp->createCommand("SELECT * FROM tbl_music_video")->queryAll();
        foreach ($musics as $music){

            $params = [':id' => $music['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $artist = Artists::find()->where(['key' => $music['directory']])->one();


            //find newAlbum id
            $params = [':id' => $music['mp3_id']];
            $mp3Meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $newMp3 = Music::find()->where(['key_pure' => $mp3Meta['key'], 'type' => Music::TYPE_MP3])->one();


            $model = Music::find()->where(['key_pure' => $meta['key'].'-video', 'type' => Music::TYPE_VIDEO])->one();
            if (!$model){
                $model = new Music();
            }

            $model->key = $meta['key'].'-video';
            $model->key_fa = $meta['key_fa'];
            $model->type = Music::TYPE_VIDEO;
            $model->name = $music['name'];
            $model->name_fa = $music['name_fa'];
            $model->artist_id = $artist->id;
            $model->special = $meta['special'] == 1 ? Music::STATUS_ACTIVE : Music::STATUS_DISABLED;
            $model->like = $music['like'];
            $model->like_fa = $music['like_fa'];
            $model->view = $music['view'];
            $model->view_fa = $music['view_fa'];
            $model->directory = $music['directory'];
            $model->dl_link = $music['dl_link'];
            $model->image = $music['image'];
            $model->status = $meta['i'] == 1 ? Music::STATUS_ACTIVE : Music::STATUS_DISABLED;
            $model->status_fa = $meta['fa'] == 1 ? Music::STATUS_ACTIVE : Music::STATUS_DISABLED;
            $model->status_app = Music::STATUS_DISABLED;
            $model->key_pure = $meta['key'].'-video';
            $model->user_id = 1;
            $model->created_at = strtotime($music['release_date']);
            $model->updated_at = strtotime($music['release_date']);
            $model->title_fa = 'دانلود موزیک ویدیو جدید ';
            $model->title_en = 'Download New Music Video By ';

            $model->music_id = !is_null($newMp3) ? $newMp3->id : null;
            $model->music_no = null;
            $model->lyric = null;
            $model->note_fa = null;

            $model->save();




            $params = [':id' => $music['director_id']];
            $directorArtist = Yii::$app->temp->createCommand("SELECT * FROM tbl_artist WHERE id=:id", $params)->queryOne();

            $params = [':id' => $directorArtist['meta_id']];
            $mp3Director = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $director = Artists::find()->where(['key' => $mp3Director['key']])->one();


            //director
            $modelArtist = MusicArtist::find()->where([
                'music_id' => $model->id,
                'artist_id' => $director->id,
                'activity' => Artists::TYPE_DIRECTOR
            ])->one();

            if (!$modelArtist){
                $modelArtist = new MusicArtist();
            }

            $modelArtist->music_id = $model->id;
            $modelArtist->artist_id = $director->id;
            $modelArtist->activity = Artists::TYPE_DIRECTOR;
            $modelArtist->save();

            //artist
            $modelMusicArtist = MusicArtist::find()->where([
                'music_id' => $model->id,
                'artist_id' => $model->artist_id,
                'activity' => Artists::TYPE_MAIN_ARTIST
            ])->one();

            if (!$modelMusicArtist){
                $modelMusicArtist = new MusicArtist();
                $modelMusicArtist->music_id = $model->id;
                $modelMusicArtist->artist_id = $model->artist_id;
                $modelMusicArtist->activity = Artists::TYPE_MAIN_ARTIST;
                $modelMusicArtist->save();
            }
        }

        $this->controller->stdout("The transfer of the Video is over.\n", Console::FG_GREEN);

    }

}
