<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use api\modules\api\frontend\v1\models\playlist\PlaylistUpdateForm;
use yii\base\Action;
use Yii;

class Update extends Action {

    /**
     * @api {put} /playlists/:id Update Playlist
     * @apiVersion 1.0.0
     * @apiName PlaylistUpdate
     * @apiGroup Playlist
     *
     * @apiParam (Param) {Integer} id Playlist identity code
     *
     * @apiParam (Data) {String} name Playlist name
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     * @apiError (Error 422) UnprocessableEntity Validation error
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
    {
        "name": [
            "نام نمی\u200cتواند خالی باشد."
        ]
    }
     *
     */
    public function run($id) {

        $model = new PlaylistUpdateForm();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->validate() && $model->save($id))
        {

            return $this->statusSuccess();
        }

        $this->statusValidation();
        return $model->getErrors();

    }
    use \api\components\ControllerStatus;
}
