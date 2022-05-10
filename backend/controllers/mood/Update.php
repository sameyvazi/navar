<?php

namespace backend\controllers\mood;

use backend\models\mood\UpdateForm;
use Yii;
use yii\base\Action;
use backend\models\artist\UploadForm;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $updateModel = new UpdateForm();

        if ($updateModel->load(Yii::$app->request->post())) {

            $updateModel->save($model);

            /*
             * upload image
             */

            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($updateModel, 'image');

            if($modelFile->imageFile != null){

                $address = Yii::$app->params['uploadUrl'].'image/mood/';
                $modelFile->upload($address, $model->image);
                Image::thumbnail($address . $model->image, 120, 120)->save($address.'medium_'.$model->image , ['quality' => 100]);

                //ftp
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($address, 'mood/', FTP_BINARY);
                Yii::$app->helper->deleteDirectoryFiles($address);
            }



            Yii::$app->session->setFlash('success', Yii::t('app', 'Mood successfully updated.'));
        }
        else
        {
            $updateModel->setAttributes($model->getAttributes());
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $updateModel,
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
            ]);
    }

}
