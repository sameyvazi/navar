<?php

namespace api\modules\api\frontend\v1\controllers\special;

use api\modules\api\frontend\v1\models\special\SpecialSearch;
use yii\base\Action;
use Yii;

class Index extends Action {

    /**
     * @api {get} /specials Special list
     * @apiVersion 1.0.0
     * @apiName SpecialIndex
     * @apiGroup Special
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
    [
        {
            "id": 1,
            "type": 1,
            "position": 1,
            "music_id": 1,
            "no": 2,
            "user_id": 1,
            "created_at": 1502271984,
            "updated_at": 1502272008
        }
    ]
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return (new SpecialSearch)->search(Yii::$app->getRequest()->get());

    }

}
