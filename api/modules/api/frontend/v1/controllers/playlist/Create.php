<?php

namespace api\modules\api\frontend\v1\controllers\playlist;

use api\modules\api\frontend\v1\models\playlist\PlaylistCreateForm;
use yii\base\Action;
use Yii;

class Create extends Action {

    use \api\components\ControllerStatus;
    /**
     * @api {post} /comments Create Playlist
     * @apiVersion 1.0.0
     * @apiName PlaylistCreate
     * @apiGroup Playlist
     * 
     * @apiParam (Data) {String} name Playlist name
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 NoContent
     * 
     * @apiError (Error 422) UnprocessableEntity Validation error
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 422 Unprocessable entity
    {
        "name": [
            "نام نمی\u200cتواند خالی باشد."
        ]
    }
     *
     * @apiError (Error 401)Unauthorized Login required
     */
    public function run() {
        
        $model = new PlaylistCreateForm();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->validate() && $model->add())
        {

            return $this->statusCreated();
        }
        
        $this->statusValidation();
        return $model->getErrors();

    }
}
