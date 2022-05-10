<?php

namespace backend\controllers\playlist;

use backend\models\artist\UploadForm;
use common\models\mood\Mood;
use common\models\playlist\Playlist;
use common\models\tag\Tag;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\imagine\Image;

class Add extends Action {

    public function run() {

        $model = new Playlist();

        $mood = Mood::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        $model->image = uniqid().'.jpg';
        $model->public = Playlist::TYPE_PUBLIC;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // upload image //
            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($model, 'image');

            if($modelFile->imageFile != null){

                $address = Yii::$app->params['uploadUrl'].'image/mood/';
                $modelFile->upload($address, $model->image);
                Image::thumbnail($address . $model->image, 300, 300)->save($address.'medium_'.$model->image , ['quality' => 100]);

                //ftp
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($address, 'mood/', FTP_BINARY);
                Yii::$app->helper->deleteDirectoryFiles($address);
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Mood successfully created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
                'mood' => $mood,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
                'mood' => $mood,
        ]);
    }

}
