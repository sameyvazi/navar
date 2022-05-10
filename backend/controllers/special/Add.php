<?php

namespace backend\controllers\special;

use common\models\artist\Artist;
use common\models\music\Music;
use common\models\playlist\Playlist;
use common\models\special\Special;
use Yii;
use yii\base\Action;

class Add extends Action {

    public function run() {

        $model = new Special();

        $music = Music::find()
            ->select(['id as value', 'key_pure as  label','id as id'])
            ->asArray()
            ->all();

        $playlist = Playlist::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        $artist = Artist::find()
            ->select(['id as value', 'key as  label','id as id'])
            ->asArray()
            ->all();

        if ($model->load(Yii::$app->request->post()) && ($model->music_id != null || $model->playlist_id != null || $model->artist_id != null)) {


            if ($model->music_id){
                $model->post_id = $model->music_id;
                $model->post_type = Special::POST_TYPE_MUSIC;
            }elseif($model->playlist_id){
                $model->post_id = $model->playlist_id;
                $model->post_type = Special::POST_TYPE_PLAYLIST;
            }elseif($model->artist_id){
                $model->post_id = $model->artist_id;
                $model->post_type = Special::POST_TYPE_ARTIST;
            }

            $model->save();

            $model->updateNo();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Special successfully created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
                'music' => $music,
                'playlist' => $playlist,
                'artist' => $artist,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
                'music' => $music,
                'playlist' => $playlist,
                'artist' => $artist,
        ]);
    }

}
