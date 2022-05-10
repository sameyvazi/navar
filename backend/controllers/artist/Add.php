<?php

namespace backend\controllers\artist;

use backend\models\artist\AddForm;
use backend\models\artist\UploadForm;
use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\imagine\Image;

class Add extends Action {

    public function run() {

        $model = new AddForm();

        $model->like = 0;
        $model->likeFa = 0;
        $model->likeApp = 0;


        if ($model->load(Yii::$app->request->post()) && $m = $model->add()) {


            /*
             * upload image
             */

            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($model, 'image');

            if($modelFile->imageFile != null){
                $modelFile->upload(Yii::$app->params['uploadUrl'].$m->key.'/cover/cover-', $m->image);
            }else{
                copy(Yii::$app->params['uploadUrl'].'default.jpg', Yii::$app->params['uploadUrl'] . $m->key . '/cover/cover-' . $m->image);
            }

            $fileName = Yii::$app->params['uploadUrl'] . $m->key . '/cover/cover-' . $m->image;
            Image::thumbnail($fileName, 250, 250)->save(Yii::$app->params['uploadUrl'].$m->key.'/cover/'.$m->image , ['quality' => 100]);
            Image::thumbnail($fileName, 180, 180)->save(Yii::$app->params['uploadUrl'].$m->key.'/cover/165'.$m->image , ['quality' => 100]);
            Image::thumbnail($fileName, 50, 50)->save(Yii::$app->params['uploadUrl'].$m->key.'/cover/50'.$m->image , ['quality' => 100]);

            //ftp
            $ftp = Yii::$app->helper->ftpLogin();
            $ftp->putAll(Yii::$app->params['uploadUrl'].$m->key.'/cover/', $m->key.'/cover/');
            Yii::$app->helper->deleteDirectoryFiles(Yii::$app->params['uploadUrl'].$m->key.'/cover/');

            Yii::$app->session->setFlash('success', Yii::t('app', 'Artist successfuly created.'));
        }
        
        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('add', [
                'model' => $model,
            ]) :
            $this->controller->render('add', [
                'model' => $model,
        ]);
    }

}
