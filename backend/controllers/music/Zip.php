<?php

namespace backend\controllers\music;

use backend\models\artist\UploadForm;
use backend\models\music\AddMusicForm;
use backend\models\music\ZipAlbumForm;
use Yii;
use yii\base\Action;
use common\models\music\Music;
use common\models\artist\Artist;
use yii\imagine\Image;
use yii\web\UploadedFile;
use getID3;

class Zip extends Action {

    public function run() {

        $model = new ZipAlbumForm();


        if ($model->load(Yii::$app->request->post()) && $m = $model->zip()) {

            Yii::$app->session->setFlash('success', Yii::t('app', 'Music successfully created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('zip', [
                'model' => $model,
            ]) :
            $this->controller->render('zip', [
                'model' => $model,
        ]);
    }

}
