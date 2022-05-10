<?php

namespace console\controllers\music;

use common\models\artist\Artist as Artists;
use common\models\music\Music;
use common\models\music\MusicArtist;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use yii\helpers\Console;
use yii\base\Action;
use Yii;
use yii2mod\ftp\FtpClient;

class Quality extends Action {

    public function run($id) {

//        $startOp = $this->controller->prompt('Are you sure start this function? ["yes" or "no"]', [
//            'required' => true,
//        ]);
//
//        if($startOp !== "yes")
//            die;

        Yii::$app->language = 'en';
        $this->controller->stdout("Transfer of Tag started...\n", Console::FG_YELLOW);


//        $musics = Music::find()
//            ->where(['>=', 'id', $id])
//            ->andWhere(['type' => Music::TYPE_MP3])
//            ->andWhere(['=', 'lq', 2])
//            ->all();

        $musics = Music::find()
            ->where(['=', 'id', $id])
            ->all();

        $storage = 'http://static.musicplus.info/storage/media/';
        foreach ($musics as $model){

            $model->hd = Music::STATUS_DISABLED;
            $model->hq = Music::STATUS_DISABLED;
            $model->lq = Music::STATUS_DISABLED;

            if ($model->type == Music::TYPE_MP3){

                $albumKey = '';
                if ($model->music_id != null && $music = Music::find()->where(['id' => $model->music_id])->one()){

                    if($music->type == Music::TYPE_ALBUM && $music->id > 6700){
                        $albumKey = $music->key_pure.'/';
                    }
                }

                $music320 = $storage.$model->directory.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].mp3');
                $music128 = $storage.$model->directory.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].mp3');
                $music96 = $storage.$model->directory.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [96].mp3');

                if (@get_headers($music320)[0] == "HTTP/1.0 200 OK" || @get_headers($music320)[0] == "HTTP/1.1 200 OK"){
                    $model->hd = Music::STATUS_ACTIVE;
                }

                if (@get_headers($music128)[0] == "HTTP/1.0 200 OK" || @get_headers($music128)[0] == "HTTP/1.1 200 OK"){
                    $model->hq = Music::STATUS_ACTIVE;
                }

                if (@get_headers($music96)[0] == "HTTP/1.0 200 OK" || @get_headers($music96)[0] == "HTTP/1.1 200 OK"){
                    $model->lq = Music::STATUS_ACTIVE;
                }

                $model->save();

            }elseif ($model->type == Music::TYPE_VIDEO){

                $video1080 = $storage.$model->directory.'/video/'.rawurlencode($model->dl_link. ' 1080p [iNavar.com].mp4');
                $video720 = $storage.$model->directory.'/video/'.rawurlencode($model->dl_link. ' 720p [iNavar.com].mp4');
                $video480 = $storage.$model->directory.'/video/'.rawurlencode($model->dl_link. ' 480p [iNavar.com].mp4');

                if (@get_headers($video1080)[0] == "HTTP/1.0 200 OK" || @get_headers($video1080)[0] == "HTTP/1.1 200 OK"){
                    $model->hd = Music::STATUS_ACTIVE;
                }

                if (@get_headers($video720)[0] == "HTTP/1.0 200 OK" || @get_headers($video720)[0] == "HTTP/1.1 200 OK"){
                    $model->hq = Music::STATUS_ACTIVE;
                }

                if (@get_headers($video480)[0] == "HTTP/1.0 200 OK" || @get_headers($video480)[0] == "HTTP/1.1 200 OK"){
                    $model->lq = Music::STATUS_ACTIVE;
                }

                $model->save();

            }elseif ($model->type = Music::TYPE_ALBUM){

                $albumKey = '';

                if($model->type == Music::TYPE_ALBUM && $model->id > 6700){
                    $albumKey = $model->key_pure.'/';
                }

                $zip320 = $storage.$model->directory.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [320].zip');
                $zip128 = $storage.$model->directory.'/mp3/'.$albumKey.rawurlencode($model->dl_link.' [128].zip');

                if (@get_headers($zip320)[0] == "HTTP/1.0 200 OK" || @get_headers($zip320)[0] == "HTTP/1.1 200 OK"){
                    $model->hd = Music::STATUS_ACTIVE;
                }

                if (@get_headers($zip128)[0] == "HTTP/1.0 200 OK" || @get_headers($zip128)[0] == "HTTP/1.1 200 OK"){
                    $model->hq = Music::STATUS_ACTIVE;
                }

                $model->save();

            }



            echo $model->id.'-';

            //die;

        }

        $this->controller->stdout("The transfer of the Tag is over.\n", Console::FG_GREEN);

    }

}
