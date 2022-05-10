<?php

namespace backend\controllers\playlistMusic;

use common\models\music\Music;
use common\models\playlist\Playlist;
use common\models\playlist\PlaylistMusic;
use Yii;
use yii\base\Action;

class Add extends Action {

    public function run() {

        $model = new PlaylistMusic();

        $music = Music::find()
            ->select(['id as value', 'key_pure as  label','id as id'])
            ->asArray()
            ->all();

        $playlist = Playlist::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->updateNo();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Playlist of Music successfully created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
                'music' => $music,
                'playlist' => $playlist,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
                'music' => $music,
                'playlist' => $playlist,
        ]);
    }

}
