<?php

namespace backend\controllers\artist;

use backend\models\artist\UpdateForm;
use common\models\tag\Tag;
use common\models\tag\TagRelation;
use Yii;
use yii\base\Action;
use backend\models\artist\UploadForm;
use yii\imagine\Image;
use yii\web\UploadedFile;

class Update extends Action {

    public function run($id) {

        $model = $this->controller->findModel($id);

        $updateModel = new UpdateForm();

        $tagRelations = TagRelation::find()->where(['post_id' => $id, 'type' => Tag::TYPE_ARTIST])->with('tags')->all();
        foreach ($tagRelations as $tag){
            $updateModel->tag .= $tag->tags->name. "\n";
        }
        $updateModel->tag = rtrim($updateModel->tag);

        if ($updateModel->load(Yii::$app->request->post())) {

            $updateModel->save($model);

            /*
             * upload image
             */

            $modelFile = new UploadForm();
            $modelFile->imageFile = UploadedFile::getInstance($updateModel, 'image');

            if($modelFile->imageFile != null){
                $modelFile->upload(Yii::$app->params['uploadUrl'].$model->key.'/cover/cover-', $model->image);

                $fileName = Yii::$app->params['uploadUrl'] . $model->key . '/cover/cover-' . $model->image;
                Image::thumbnail($fileName, 250, 250)->save(Yii::$app->params['uploadUrl'].$model->key.'/cover/'.$model->image , ['quality' => 100]);
                Image::thumbnail($fileName, 180, 180)->save(Yii::$app->params['uploadUrl'].$model->key.'/cover/165'.$model->image , ['quality' => 100]);
                Image::thumbnail($fileName, 50, 50)->save(Yii::$app->params['uploadUrl'].$model->key.'/cover/50'.$model->image , ['quality' => 100]);

                //ftp
                $ftp = Yii::$app->helper->ftpLogin();
                $ftp->putAll(Yii::$app->params['uploadUrl'].$model->key.'/cover/', $model->key.'/cover/');
                Yii::$app->helper->deleteDirectoryFiles(Yii::$app->params['uploadUrl'].$model->key.'/cover/');
            }



            Yii::$app->session->setFlash('success', Yii::t('app', 'Artist successfuly updated.'));
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
