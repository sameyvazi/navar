<?php

namespace console\controllers\music;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\music\MusicArtist;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Album extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Albums started...\n", Console::FG_YELLOW);

        $musics = Yii::$app->temp->createCommand("SELECT * FROM tbl_music_album WHERE id!=13")->queryAll();
        foreach ($musics as $music){

            $params = [':id' => $music['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();
            $artist = Artist::find()->where(['key' => $music['directory']])->one();

            $model = Music::find()->where(['key_pure' => $meta['key'].'-album', 'type' => Music::TYPE_ALBUM])->one();

            if (!$model){
                $model = new Music();
            }

            $model->key = $meta['key'].'-album';
            $model->key_fa = $meta['key_fa'];
            $model->type = Music::TYPE_ALBUM;
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
            $model->status_app = Music::STATUS_ACTIVE;
            $model->key_pure = $meta['key'].'-album';
            $model->music_no = 0;
            $model->user_id = 1;
            $model->created_at = strtotime($music['release_date']);
            $model->updated_at = strtotime($music['release_date']);
            $model->title_fa = 'دانلود آلبوم جدید ';
            $model->title_en = 'Download New Album By ';

            $model->save();


            $modelMusicArtist = MusicArtist::find()->where([
                'music_id' => $model->id,
                'artist_id' => $model->artist_id,
                'activity' => Artist::TYPE_MAIN_ARTIST
            ])->one();

            if (!$modelMusicArtist){
                $modelMusicArtist = new MusicArtist();
                $modelMusicArtist->music_id = $model->id;
                $modelMusicArtist->artist_id = $model->artist_id;
                $modelMusicArtist->activity = Artist::TYPE_MAIN_ARTIST;
                $modelMusicArtist->save();
            }
        }

        $this->controller->stdout("The transfer of the Albums is over.\n", Console::FG_GREEN);
        
    }

}
