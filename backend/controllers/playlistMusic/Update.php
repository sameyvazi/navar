<?php

namespace backend\controllers\playlistMusic;

use backend\models\playlistMusic\UpdateForm;
use common\models\music\Music;
use common\models\playlist\Playlist;
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

        if ($updateModel->load(Yii::$app->request->post()) && $updateModel->save($model)) {

            Yii::$app->session->setFlash('success', Yii::t('app', 'PlaylistMusic successfully updated.'));
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
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
                'music' => $music,
                'playlist' => $playlist,
            ]);
    }

}
