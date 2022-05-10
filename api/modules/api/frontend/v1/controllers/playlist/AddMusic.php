<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use api\modules\api\frontend\v1\models\playlist\PlaylistAddMusicForm;
use yii\base\Action;
use Yii;

class AddMusic extends Action {

    use \api\components\ControllerStatus;
    /**
     * @api {post} /playlists/add Add music to play list
     * @apiVersion 1.0.0
     * @apiName PlaylistAddMusic
     * @apiGroup Playlist
     * 
     * @apiParam (Data) {Integer} playlist_id Playlist id
     * @apiParam (Data) {Integer} music_id Music id
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 NoContent
     * 
     * @apiError (Error 422) UnprocessableEntity Validation error
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
            "Playlist ID نمی\u200cتواند خالی باشد."
        ],
        "music_id": [
            "Music ID نمی\u200cتواند خالی باشد."
        ]
    }
     */
    public function run() {
        
        $model = new PlaylistAddMusicForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->validate() && $model->add())
        {

            return $this->statusCreated();
        }
        
        $this->statusValidation();
        return $model->getErrors();

    }
}
