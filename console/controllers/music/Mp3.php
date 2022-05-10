<?php

namespace console\controllers\music;

use common\models\artist\Artist  as Artists;
use common\models\music\Music;
use yii\helpers\Console;
use yii\base\Action;
use Yii;

class Mp3 extends Action {

    public function run() {

        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
            'required' => true,
        ]);

        if($startOp !== "yes")
            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Mp3 started...\n", Console::FG_YELLOW);

        $musics = Yii::$app->temp->createCommand("SELECT * FROM tbl_music_mp3")->queryAll();
        foreach ($musics as $music){

            $params = [':id' => $music['meta_id']];
            $meta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $artist = Artists::find()->where(['key' => $music['directory']])->one();

            //find newAlbum id
            $params = [':id' => $music['album_id']];
            $album = Yii::$app->temp->createCommand("SELECT * FROM tbl_music_album WHERE id=:id", $params)->queryOne();

            $params = [':id' => $album['meta_id']];
            $albumMeta = Yii::$app->temp->createCommand("SELECT * FROM tbl_meta_key WHERE id=:id", $params)->queryOne();

            $newAlbum = null;
            if ($album['id'] != 13){
                $newAlbum = Music::find()->where(['key_pure' => $albumMeta['key'].'-album', 'type' => Music::TYPE_ALBUM])->one();
            }


            $model = Music::find()->where(['key_pure' => $meta['key'], 'type' => Music::TYPE_MP3])->one();
            if (!$model){
                $model = new Music();
            }

            $model->key = $meta['key'];
            $model->key_fa = $meta['key_fa'];
            $model->type = Music::TYPE_MP3;
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
            $model->key_pure = $meta['key'];
            $model->user_id = 1;
            $model->created_at = !is_null($newAlbum) ? strtotime($albumMeta['release_date']) : strtotime($music['release_date']);
            $model->updated_at = !is_null($newAlbum) ? strtotime($albumMeta['release_date']) : strtotime($music['release_date']);
            $model->title_fa = 'دانلود آهنگ جدید ';
            $model->title_en = 'Download New Song By ';

            $model->music_id = !is_null($newAlbum) ? $newAlbum->id : null;
            $model->music_no = $music['track_number'];
            $model->lyric = $music['lyric'];
            $model->note_fa = $music['note_fa'];

            $model->save();
        }

        $this->controller->stdout("The transfer of the Mp3s is over.\n", Console::FG_GREEN);
        
    }

}
