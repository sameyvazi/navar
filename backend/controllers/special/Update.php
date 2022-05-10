<?php

namespace backend\controllers\special;

use backend\models\special\UpdateForm;
use common\models\artist\Artist;
use common\models\music\Music;
use common\models\playlist\Playlist;
use common\models\special\Special;
use Yii;
use yii\base\Action;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $updateModel = new UpdateForm();

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

        if ($model->post_type == Special::POST_TYPE_MUSIC){
            $updateModel->music_id = $model->post_id;
        }elseif ($model->post_type == Special::POST_TYPE_PLAYLIST){
            $updateModel->playlist_id = $model->post_id;
        }elseif ($model->post_type == Special::POST_TYPE_ARTIST){
            $updateModel->artist_id = $model->post_id;
        }

        if ($updateModel->load(Yii::$app->request->post())) {

            if ($updateModel->music_id){
                $updateModel->post_id = $updateModel->music_id;
                $updateModel->post_type = Special::POST_TYPE_MUSIC;
            }elseif($updateModel->playlist_id){
                $updateModel->post_id = $updateModel->playlist_id;
                $updateModel->post_type = Special::POST_TYPE_PLAYLIST;
            }elseif($updateModel->artist_id){
                $updateModel->post_id = $updateModel->artist_id;
                $updateModel->post_type = Special::POST_TYPE_ARTIST;
            }

            $updateModel->save($model);

            //$model->updateNo();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Artist successfully updated.'));
        }
        else
        {
            $updateModel->setAttributes($model->getAttributes());
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $updateModel,
                'music' => $music,
                'playlist' => $playlist,
                'artist' => $artist,
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
                'music' => $music,
                'playlist' => $playlist,
                'artist' => $artist,
            ]);
    }

}
