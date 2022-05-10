<?php

namespace backend\controllers\music;

use backend\models\artist\UploadForm;
use backend\models\music\AddMusicForm;
use Yii;
use yii\base\Action;
use common\models\music\Music;
use common\models\artist\Artist;
use yii\imagine\Image;
use yii\web\UploadedFile;
use getID3;

class View extends Action {

    public function run($id) {

        error_reporting(E_ALL);

        /* Add redirection so we can get stderr. */
        //$handle = popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii album/song '.$id, 'r');
        $handle = popen('cd '.\Yii::getAlias('@app').' && cd .. && php yii music/quality '.$id, 'r');
        echo "'$handle'; " . gettype($handle) . "\n";
        $read = fread($handle, 2096);
        echo $read;
        pclose($handle);

        //die;

        $model = $this->controller->findModel($id);


        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('view', [
                'model' => $model,
            ]) :
            $this->controller->render('view', [
                'model' => $model,
        ]);
    }
}
