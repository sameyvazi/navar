<?php

namespace backend\controllers\playlist;

use backend\models\playlist\UpdateForm;
use common\models\mood\Mood;
use common\models\tag\Tag;
use Yii;
use yii\base\Action;
use backend\models\artist\UploadForm;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $updateModel = new UpdateForm();

        $mood = Mood::find()
            ->select(['id as value', 'name as  label','id as id'])
            ->asArray()
            ->all();

        if ($updateModel->load(Yii::$app->request->post())) {

            $updateModel->save($model);

            // upload image
            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($updateModel, 'image');

            if($modelFile->imageFile != null){

                $address = Yii::$app->params['uploadUrl'].'image/mood/';
                $modelFile->upload($address, $model->image);
                Image::thumbnail($address . $model->image, 300, 300)->save($address.'medium_'.$model->image , ['quality' => 100]);

                //ftp
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll($address, 'mood/', FTP_BINARY);
                Yii::$app->helper->deleteDirectoryFiles($address);
            }



            Yii::$app->session->setFlash('success', Yii::t('app', 'Playlist successfully updated.'));
        }
        else
        {
            $updateModel->setAttributes($model->getAttributes());
        }

        return Yii::$app->request->isAjax ?
            $this->controller->renderAjax('manage', [
                'model' => $updateModel,
                'mood' => $mood,
            ]) :
            $this->controller->render('manage', [
                'model' => $updateModel,
                'mood' => $mood,
            ]);
    }

}
