<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use common\models\playlist\Playlist;
use yii\base\Action;
use Yii;

class Delete extends Action {

    use \api\components\ControllerStatus;
    /**
     * @api {delete} /playlists/:id Delete Playlist
     * @apiVersion 1.0.0
     * @apiName PlaylistDelete
     * @apiGroup Playlist
     *
     * @apiParam (Params) {Integer} id Playlist id
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 ok
     * 
     * @apiError (Error 422) UnprocessableEntity Validation error
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */
    public function run($id) {


        $type = Yii::$app->request->headers->get('type');
        if (is_null($type)) {
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'Send the client type in the header!'));
        }

        $model = Playlist::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();
        if ($model !== null && $model->delete())
        {

            return $this->statusSuccess();
        }else{
            throw new \yii\web\NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
        
        $this->statusValidation();
        return $model->getErrors();

    }
}
