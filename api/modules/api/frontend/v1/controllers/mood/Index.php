<?php

namespace api\modules\api\frontend\v1\controllers\mood;

use yii\base\Action;
use Yii;
use api\modules\api\frontend\v1\models\mood\MoodSearch;

class Index extends Action {

    /**
     * @api {get} /moods Mood Index
     * @apiVersion 1.0.0
     * @apiName MoodIndex
     * @apiGroup Mood
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *
    [
        {
            "id": 2,
            "name": "acasc",
            "name_fa": "cacsdc",
            "image": "59903cd3cd11f.jpg",
            "no": 0
        },
        {
            "id": 3,
            "name": "aaaa",
            "name_fa": "aaaa",
            "image": "59901e9a77305.jpg",
            "no": 0
        }
    ]
     *
     *
     *
     * @apiError (Error 401)Unauthorized Login required
     *
     * @apiError (Error 404)NotFound Type required
     */

    public function run() {

        return (new MoodSearch)->search(Yii::$app->getRequest()->get());

    }

}
