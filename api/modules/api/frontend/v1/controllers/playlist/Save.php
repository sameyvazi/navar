<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use api\modules\api\frontend\v1\models\playlist\PlaylistSave;
use yii\base\Action;
use Yii;

class Save extends Action {

    use \api\components\ControllerStatus;
    /**
     * @api {post} /playlists/save Playlist Save
     * @apiVersion 1.0.0
     * @apiName PlaylistSave
     * @apiGroup Playlist
     * 
     * @apiParam (Data) {Integer} id Playlist identity code
     *
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
    {
        "playlist_id": [
            "Playlist نمی\u200cتواند خالی باشد."
        ]
    }
     */
    public function run() {

        $model = new PlaylistSave();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->validate() && $model->add())
        {

            return $this->statusCreated();
        }

        $this->statusValidation();
        return $model->getErrors();

    }

}
